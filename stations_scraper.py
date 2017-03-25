import requests
import codecs
import json
import sys
from bs4 import BeautifulSoup


def get_lat_lng(station_name, stops):
    """Try to find station name in stops file, getting lat and lng."""
    for line in stops:
        if station_name.lower() in line.lower():
            l = line.replace("\n", "").split(",")
            lat = l[4].replace('\"', '')
            lng = l[5].replace('\"', '')
            return lat, lng
    return 0, 0


def get_stations_from_wiki():
    # Get from wikipedia
    wiki_url = "https://en.wikipedia.org/wiki/List_of_Milan_Metro_stations"
    client = requests.session()
    r = client.get(wiki_url)
    soup = BeautifulSoup(r.content, 'html.parser')
    table_body = soup.select("table.wikitable.sortable")[0]
    # Stations csv
    stations = codecs.open("stations.csv", "w", encoding="utf-8")
    stations.write("Name,Lines,Lat,Lng\n")
    with codecs.open("stops_stripped.csv", "r", encoding="utf-8") as f:
        stops = [line for line in f.readlines()]
    # Go through rows
    for row in table_body.find_all("tr")[1:]:
        cells = row.find_all("td")
        name = (cells[0]).find("a").text
        lat, lng = get_lat_lng(name, stops)
        # Ignore stations that were not in the dataset
        if lat == 0 and lng == 0:
            continue
        # Get the lines
        line1 = (((cells[2]).find("img")["alt"]).split(" ")[-1]).split(".")[0]
        line2 = ''
        line3 = ''
        if len((cells[2]).find_all("img")) > 1:
            pot_line = (((cells[2]).find_all("img")[1])["alt"]).split(" ")[-1]
            if 'M' in pot_line:
                line2 = pot_line.split(".")[0]
        if len((cells[2]).find_all("img")) > 2:
            pot_line = (((cells[2]).find_all("img")[2])["alt"]).split(" ")[-1]
            if 'M' in pot_line:
                line3 = pot_line.split(".")[0]
        line = " ".join([line1, line2, line3])
        stations.write(name.lower() + "," + line + "," + lat + "," + lng +
                       "\n")
    stations.close()


def format_zones():
    with open("www/public/milan-grid.geojson", "r") as data:
        zones = json.load(data)
    coords = [[]]
    for index, feature in enumerate(zones["features"]):
        geo = feature["geometry"]["coordinates"][0]
        square = {
            "id": feature["id"],
            "up_left": geo[0],
            "up_right": geo[1],
            "down_left": geo[2],
            "down_right": geo[3]
        }
        if (index + 1) % 100 == 0:
            coords.append([])
        coords[index // 100].append(square)
        index += 1
    return coords


def lat_is_in(lat, zone):
    if float(lat) <= float(zone["up_left"][1]) and float(lat) >= \
            float(zone["down_left"][1]):
        return True
    if float(lat) <= float(zone["up_right"][1]) and float(lat) >= \
            float(zone["down_right"][1]):
        return True
    return False


def lng_is_in(lng, zone):
    print(lng)
    print(zone)
    if float(lng) <= float(zone["up_right"][0]) and float(lng) >= \
            float(zone["up_left"][0]):
        return True
    if float(lng) >= float(zone["down_right"][0]) and float(lng) <= \
            float(zone["down_left"][0]):
        return True
    return False


def search_by_lat(start, end, zones, lat):
    """Binary search."""
    if start >= end:
        return -1
    mid = (start + end) // 2
    if lat_is_in(lat, zones[mid][50]):
        return mid
    mid_zone = zones[mid][50]
    if float(lat) >= float(mid_zone["up_left"][1]) or float(lat) >= \
            float(mid_zone["up_right"][1]):
        return search_by_lat(mid + 1, end, zones, lat)
    else:
        return search_by_lat(start, mid, zones, lat)


def search_by_lng(start, end, zones, lng):
    """Binary search."""
    if start >= end:
        return -1
    mid = (start + end) // 2
    if lng_is_in(lng, zones[50][mid]):
        return mid
    mid_zone = zones[50][mid]
    if float(lng) >= float(mid_zone["up_right"][0]) or float(lng) >= \
            float(mid_zone["down_right"][0]):
        return search_by_lng(mid + 1, end, zones, lng)
    else:
        return search_by_lng(start, mid, zones, lng)


def search_zone(lat_lng, zones):
    lat = lat_lng[0]
    lng = lat_lng[1]
    # Do binary search
    lat_index = search_by_lat(0, 99, zones, lat)
    lng_index = search_by_lng(0, 99, zones, lng)
    zone = zones[lat_index][lng_index]
    return zone["id"]


def get_stations_zones():
    zones = format_zones()
    with codecs.open("stations.csv", "r", encoding="utf-8") as f:
        stations = [line.replace("\n", '').split(",") for line in
                    f.readlines()]
    new_stations = codecs.open("stations_enhanced.csv", "w", encoding="utf-8")
    new_stations.write("Name,Lines,Lat,Lng,Zone ID\n")
    for station in stations[1:]:
        print(station)
        zone_id = search_zone((station[2][:9], station[3][:9]), zones)
        print(zone_id)
        new_stations.write(station[0] + "," + station[1] + "," +
                           station[2][:9] + "," + station[3][:9] +
                           "," + str(zone_id) + "\n")
    new_stations.close()


def main():
    get_stations_zones()


if __name__ == '__main__':
    main()

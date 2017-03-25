import requests
import codecs
import sys
from bs4 import BeautifulSoup


def get_lat_lng(station_name, stops):
    """Try to find station name in stops file, getting lat and lng."""
    for line in stops:
        if station_name.lower() in line.lower():
            l = line.replace("\n", "").split(",")
            lat = l[4]
            lng = l[5]
            return lat, lng
    return 0, 0


def main():
    # Get from wikipedia
    wiki_url = "https://en.wikipedia.org/wiki/List_of_Milan_Metro_stations"
    client = requests.session()
    r = client.get(wiki_url)
    soup = BeautifulSoup(r.content, 'html.parser')
    table_body = soup.select("table.wikitable.sortable")[0]
    # Stations csv
    stations = codecs.open("stations.csv", "w", encoding="utf-8")
    stations.write("Name,Lines,Lat,Lng\n")
    with codecs.open("stops.csv", "r", encoding="utf-8") as f:
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


if __name__ == '__main__':
    main()

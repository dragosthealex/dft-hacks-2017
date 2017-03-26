import json
import codecs
import sys
import os
from tqdm import tqdm


def get_levels(lines):
    levels = []
    for l in lines[1:]:
        if len(l) != 6:
            continue
        lv = (l[4].strip() + ' ' + l[5].strip()).replace('\"', '')
        if (lv) not in levels:
            levels.append(lv)
    levels.sort()
    levels_dict = {}
    for index, lvl in enumerate(levels):
        levels_dict[lvl] = index
    return levels_dict


def compile_json_files():
    data_dir = "data/data_days"
    for x in os.listdir(data_dir):
        f = open(data_dir + "/" + x, "r")
        jsn = open(x.replace(".csv", "") + ".json", "w")
        # Read the lines
        lines = [line.replace("\n", "").split(",") for line in f.readlines()]
        # Find out levels
        levels = get_levels(lines)
        frames = {}
        for line in tqdm(lines[1:]):
            if len(line) != 6:
                continue
            unix = line[2]
            zone = line[1]
            lv = (line[4].strip() + ' ' + line[5].strip()).replace('\"', '')
            level = levels[lv]
            density = line[3]
            if unix == '' or zone == '' or lv == '' or density == '':
                continue
            # Put in json
            frame_zone = {
                "level": level,
                "density": density
            }
            if unix not in frames:
                frames[unix] = {}
            frames[unix][zone] = frame_zone
        jsn.write(json.dumps(frames))
        jsn.close()
        f.close()


def compile_json_files2():
    data_dir = "data/final_2_files"
    for x in os.listdir(data_dir):
        f = open(data_dir + "/" + x, "r")
        jsn = open(x.replace(".csv", "") + ".json", "w")
        # Read the lines
        lines = [line.replace("\n", "").split(",") for line in f.readlines()]
        frames = {}
        for line in tqdm(lines[1:]):
            if len(line) != 6:
                continue
            unix = line[2]
            zone = line[1]
            level = line[4]
            density = line[5]
            # Put in json
            frame_zone = {
                "level": level,
                "density": density
            }
            if unix not in frames:
                frames[unix] = {}
            frames[unix][zone] = frame_zone
        jsn.write(json.dumps(frames))
        jsn.close()
        f.close()


def create_json_stations():
    f = codecs.open("stations_enhanced.csv", "r", encoding="utf-8")
    jsn = codecs.open("stations.json", "w", encoding="utf-8")
    lines = [line.replace("\n", "").replace('\"', '').replace("\r", "").
             split(",") for line in f.readlines()]
    obj = {}
    for line in lines[1:]:
        station_name = line[0]
        ln = line[1].strip()
        zone = line[4]
        station = {
            "name": station_name,
            "lines": ln
        }
        if zone in obj:
            obj[zone].append(station)
        else:
            obj[zone] = [station]
    jsn.write(json.dumps(obj))
    f.close()
    jsn.close()


def main():
    compile_json_files2()

if __name__ == '__main__':
    main()

# -*- coding: utf-8 -*-
import sys
import nltk
import codecs
import requests
import config as cfg
from bs4 import BeautifulSoup
from tqdm import tqdm

TRANSLATE_API_KEY = cfg.MS_TRANSLATE_API_KEY


def get_token():
    headers = {
        'Ocp-Apim-Subscription-Key': TRANSLATE_API_KEY,
    }
    url = "https://api.cognitive.microsoft.com/sts/v1.0/issueToken"
    r = requests.post(url, data={}, headers=headers)
    return r.content


def ms_translate(text):
    good_token = get_token()
    data = {
        "text": text,
        "to": "en",
        "from": "it",
        "appid": "Bearer " + good_token
    }
    url = "http://api.microsofttranslator.com/v2/Http.svc/Translate"
    r = requests.get(url, params=data)
    soup = BeautifulSoup(r.content, 'html.parser')
    translated_text = soup.find("string").text
    return translated_text


def translate_tweets(until=0):
    f = codecs.open("tweets.csv", "r", encoding="utf-8")
    f2 = codecs.open("tweets_english.csv", "w", encoding="utf-8")
    f2.write("Datetime,Unix Timestamp,Text\n")
    index = 0
    lines = [line for line in f.readlines() if line != "\n"]
    if until != 0:
        lines = lines[:until + 1]
    print(str(len(lines) - 1)) + " lines"
    for line in tqdm(lines, total=(len(lines) - 1)):
        if until != 0 and index == until + 1:
            f.close()
            f2.close()
            return
        if index == 0:
            index += 1
            continue
        if line == "" or line is None or line == "\n":
            continue
        line = line.replace("\n", "").split(",")
        hrt = line[0]
        text = line[1]
        us = line[2]
        trans = ms_translate(text)
        f2.write(hrt + "," + us + "," + trans + "\n")
        index += 1
    f.close()
    f2.close()


if __name__ == "__main__":
    translate_tweets(0)

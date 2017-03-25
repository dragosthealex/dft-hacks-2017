import time
import codecs
from selenium import webdriver
from selenium.webdriver.common.keys import Keys


browser = webdriver.Chrome()
url = u'https://twitter.com/search?l=&q=from%3Aatm_informa%20since' +\
    '%3A2013-11-01%20until%3A2014-01-01&src=typd&lang=en'

browser.get(url)
time.sleep(1)

body = browser.find_element_by_tag_name('body')

for _ in range(1000):
    body.send_keys(Keys.PAGE_DOWN)
    time.sleep(0.2)

tweets = browser.find_elements_by_class_name('original-tweet')

f = codecs.open('tweets.csv', 'w', encoding="utf-8")
f.write("Date-time,Text, Unix Timestamp\n")
for tweet in tweets:
    timestamp = tweet.find_element_by_class_name('tweet-timestamp')
    timestamp_human = timestamp.get_attribute('title')
    timestamp_unix = timestamp.find_element_by_class_name('_timestamp').\
        get_attribute('data-time')
    text = tweet.find_element_by_class_name('tweet-text').text
    if timestamp_human is None or text is None or timestamp_unix is None:
        continue
    f.write(timestamp_human + "," + text + "," + timestamp_unix + "\n")
f.close()

# -*- coding: utf-8 -*-
import sys
import requests
import datetime
from urllib import quote
morning = ['09:00:00','13:00:00']
afternoon = ['13:00:00','18:00:00']
night = ['18:00:00','9:00:00']

url = 'http://tieba.baidu.com/f/commit/post/add'

def content(neirong):
    nei = quote(neirong)
    cookie = {
        'BAIDUID':'6F29D2B9EC7F6D24AA65E0C70E65AF28:FG=1', 
        'BIDUPSID':'6F29D2B9EC7F6D24AA65E0C70E65AF28', 
        'PSTM':'1530369810', 
        '__cfduid':'dc389c3ded2cc32292157923593f193c31530543753', 
        'TIEBA_USERTYPE':'aa133321fc58012e96140f59', 
        'Hm_lvt_98b9d8c2fd6608d564bf2ac2ae642948':'1531202677,1531371462', 
        'TIEBAUID':'dbdb3be104ab4a93ce181a0d', 
        'bdshare_firstime':'1531202744031', 
        'FP_UID':'6177f71bfe08c1028a35aefe3f206e06', 
        'BDRCVFR[gltLrB7qNCt]':'mk3SLVN4HKm', 
        'PSINO':'1', 
        'H_PS_PSSID':'1426_25809_21097_26350_20718', 
        'BDRCVFR[Fc9oatPmwxn]':'G01CoNuskzfuh-zuyuEXAPCpy49QhP8', 
        'Hm_lpvt_98b9d8c2fd6608d564bf2ac2ae642948':'1531371757', 
        'wise_device':'0', 
        '986221199_FRSVideoUploadTip':'1', 
        'BDRCVFR[feWj1Vr5u3D]':'I67x6TjHwwYf0', 
        'rpln_guide':'1', 
        'BDUSS':'VlzWTlFcHdWajhULVlDT1FhbHd4TW9jUVVRWUtQRXRxNlhRajREZC1FckxiVzViQVFBQUFBJCQAAAAAAAAAAAEAAADSVfGlbGlmZcCywLLAsjA1MjIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMvgRlvL4EZbe', 
        'STOKEN':'c7b03d01ba4b0f81a0c8378a856c3c404c38fb9fdf4a9d6ffe6e7c4b7d3d5d71', 
        '2784056786_FRSVideoUploadTip':'1'
    }
    data = 'ie=utf-8&kw=%E6%9D%AD%E5%B7%9E%E7%94%B5%E5%AD%90%E7%A7%91%E6%8A%80%E5%A4%A7%E5%AD%A6&fid=443845&tid=5788662099&vcode_md5=&floor_num=66&rich_text=1&tbs=66f39225a80677031531371866&content=%e6%af%8f%e6%97%a5%e4%b8%89%e9%a1%b6&basilisk=1&files=%5B%5D&mouse_pwd=121%2C121%2C114%2C102%2C125%2C115%2C127%2C122%2C67%2C123%2C102%2C122%2C102%2C123%2C102%2C122%2C102%2C123%2C102%2C122%2C102%2C123%2C102%2C122%2C102%2C123%2C102%2C122%2C67%2C123%2C126%2C121%2C122%2C127%2C67%2C123%2C120%2C114%2C122%2C102%2C114%2C122%2C122%2C15313718765740&mouse_pwd_t=1531371876574&mouse_pwd_isclick=0&__type__=reply&_BSK=JVwFUmcLBl4rBkM6AiUQWQ8DZ3t4VUYVBG0BRylGI3xzQ2NNCWkwDwBWcXUFHghTaU4%2BWA01FGU%2FTENZZQkUG2lXVm5DdlYCWlZyZ28URBscIFYEfxckMSJZPAZAIhscOUVtPQMPBks%2BHH0OXGAUdnIdDQw2VAhYMRpDcwcjFUUZSyc7L1dZWE4oCQokWSMiJRwzAF0vLQ8YHy0lC0BaHWVYLV0QJBQ3MQ0VLiBCV0oiEB88ADwCRRgCACE%2FSwFEEj8CSiBUNTUURjUBRzpoGhBHDjIISEkdIEcxGBk1TAQxExEWMVRAeDEMXzpNIRdECQ8IMj5MFBtTIhFDEVpqPT5GNS1KZTYYBlonMjBCBhssWzZOGxJBZXJcFFJnCwZmKg9aMw0tWQVEV2V%2FF0QWXlA5CFUtDmYZP0Q1AxMEJR5VfA53PA0bWWcZbA9eIk59aE9PU2wRY04mHlxwU3xHAFpWdWZ6YxxFWysIXmoDd35hEnxNUn1mR1VVPDsXSAZLJxt9Dl5iCHZpTlRSdB0GQnRXCX8VPgNVRkU1ZngfVxIJD0IUd0EkI3QCYkoACGFPRwVrMVcUGFt8SWcESGcPd21PVFB0AhMafUMFelN%2BUwcuRWl1LRdXDRwDMmoJF2pyIgJyVRN4dkVFH387Vg8QST1aKlFSclZ1fERBFzdEQQdnBwJ9W24QRQQEMT41S1VFXyMDSSgdb3AqEHBPExIqHAFaKzJETkUNLHUiFlJyTHZ8REMFMF9HXywaXX8VIyVEGA4rMHIMVUwebUcGHlsnJDhGNU9QJiAYKE5%2Fe0ZMG0tzCG4GRmAUZT1PQ1llRVZeIFkRMVBuTBBYV3RgahBEBhJvEBdnD2QeBHwcTR9rN0xXCX1vVB0GSzkafQ5cHVkkFxAVBikTCAkxRxFlQX1DA1tUcmZiHEQbHCFWBH8XPDh8cx5NH2szTlcJfTEFQVkMNA%3D%3D'
    html = requests.post(url=url,cookies=cookie,data=data)
    print html.text


data = ''
now = datetime.datetime.now().strftime("%H:%M:%S")
if now > morning[0] and now < morning[1]:
    data = '打卡(1/3)'
elif now > afternoon[0] and now < afternoon[1]:
    data = '打卡(2/3)'
else:
    data = '打卡(3/3)'

x = sys.argv[1]
con = x + data
content(con)
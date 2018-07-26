# from hashlib import *

# for i in range(10000000):
#     # print md5(str(i)).hexdigest()[:6]
#     if md5(str(i)).hexdigest()[:6] == 'd284cc':
#         print i
#         break

import requests
from hashlib import md5
import re

# x = 'CVE-2017-1000499_CWE-352_2018-06-15 09:40:12'.lower()
# print md5(x).hexdigest()
# print "1"
req = requests.session()
url = 'http://101.71.29.5:10002//flag.php'

data = dict(answer = md5(str(2)).hexdigest())

html = req.post(url, data=data)

x = re.findall('--(.*?)--',html.content)

html = req.post(url,data= dict(answer = x[0]))

print html.text

# url1 = 'http://101.71.29.5:10003/admin'

# html = req.post(url1,data= dict(answer = x[0]))

# print html.text
# import sys

# print sys.path
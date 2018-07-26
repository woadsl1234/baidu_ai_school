# -*- coding: utf-8 -*-
import requests
import sys

re = requests.session()
cookie = {
    'ASP.NET_SessionId':'mngk1h551l4fkv55ztmq1h45',
    'route':'3d0c690852e4ff1f04aa9cef8f2994ef'
}

def image(x):
    url = 'http://jxgl.hdu.edu.cn/readimagexs.aspx?xh={}&lb=xsdzzcxx'.format(x)
    html = re.get(url, cookies = cookie)
    # print(html.content)
    f = open('image.png','wb')
    f.write(html.content)
    f.close()
try:
    x = sys.argv[1]
    image(x)
except:
    print("在后面输入学号就可以了")

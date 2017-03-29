import requests
url = "http://192.168.99.100"
b = bytearray()
b.extend([1,2,3])	# cmd
b.extend("cba")		# arg
r = requests.post(url, data=b)
print r.text
import gtk
clipboard = gtk.clipboard_get()

f = open('coords', 'r')
c = f.read()
ls = c.split('\n')

output = []
for l in ls:
	if len(l):
		coords = l.split(' ')
		output.append('new google.maps.LatLng(' + str(coords[0]) + ', ' + str(coords[1]) + ')')

clipboard.set_text(', \n'.join(output))

try:
	raw_input('Contents in the clipboard. Paste it somewhere and press enter.')
except EOFError:
	exit()
except KeyboardInterrupt:
	exit()

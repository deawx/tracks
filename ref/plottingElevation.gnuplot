set terminal png truecolor
set xdata time
set timefmt "%Y-%m-%d.%H:%M:S"
set ylabel "Meters"
set title "Elevation Profile"
set nokey
#set offset 0, 0, graph .01, 0
set offset 0, 0, .01, 0
plot "< cat /proc/$$/fd/0" using 1:2 with lines

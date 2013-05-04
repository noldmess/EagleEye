	for img in *.pgm; do
	    filename=${img%.*}
	    convert "$filename.pgm" "$filename.jpg"
	done

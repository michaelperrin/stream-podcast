# stream-podcast

## Use of VLC

A `Dockerfile` was made to run VLC in command line (automatically avoids to be run as root, a thing that VLC doesn't like, at all).

Example (save stream of KCRW for 10 seconds, and save it to KCRW.mp3 file in the *audio* directory):

    docker-compose run --rm vlc "http://media.kcrw.com/pls/kcrwsimulcast.pls" --sout file/mp3:/audio/KCRW.mp3 --run-time=10 --stop-time=10 vlc://quit

## Usage

Run:

    make start_dev

Visit http://localhost:8083/npr-morning-edition.xml


## Resources

* https://blog.sourcefabric.org/en/news/blog/2076/Schedule-stream-recordings-from-the-command-line-Part-1.htm
* https://blog.sourcefabric.org/en/news/blog/2077/Schedule-stream-recordings-from-the-command-line-Part-2.htm

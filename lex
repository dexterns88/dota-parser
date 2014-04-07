#!/bin/bash
clear # clear screen so we can follow what is going on
# parse arguments and then call appropriate module

case "$1" in
'cc')  php ./vendor/lex/twig_cache.php $*
    ;;
*)  echo "Nothing to do here. Exiting..."
    ;;
esac

echo \

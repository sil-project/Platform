#!/usr/bin/env bash
set -e



for i in 'Sil/Component' 'Sil/Bundle' 'Blast/Component' 'Blast/Bundle'
do
    for j in fr en
    do
        cd doc/$j/$i
        rm -f map.rst.inc toctree.rst.inc
        touch map.rst.inc
        echo '.. toctree::
    :hidden:
' > toctree.rst.inc
        for k in *;
        do
            if [ -d $k ];
            then
                echo '* :doc:`/'$i'/'$k'/index`' >> map.rst.inc;
                echo '    '$k'/index' >> toctree.rst.inc;
            fi;
        done
        git add map.rst.inc
        git add toctree.rst.inc
        cd -
    done
done

#/bin/sh
################################
# EDIT HERE!
NEW_PROJECT_NAME=foobar-v2
NS2NDLEVEL=Foobar
NAME=$(git config --get user.name)
EMAIL=$(git config --get user.email)
LICENSE=MIT

################################
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
cd $SCRIPTPATH/..
rm -f composer.lock

# backup template files
mv LICENSE .provision/LICENSE
mv README.md .provision/README.md

LICENSE_FILE=LICENSE
if [ "$LICENSE" = "MIT" ]
then
  wget -O $LICENSE_FILE https://raw.githubusercontent.com/github/choosealicense.com/gh-pages/_licenses/mit.txt
  YEAR=$(date +%Y)
  sed -i '1,/^---/d' $LICENSE_FILE
  sed -i -e "s/\[year\]/$YEAR/" $LICENSE_FILE
  sed -i -e "s/\[fullname\]/$NAME/" $LICENSE_FILE
  sed -i -e "s/\"license\":.*$/\"license\": \"MIT\",/" composer.json
else
  sed -i -e "s/\"license\":.*$/\"license\": \"proprietary\",/" composer.json
fi
# misc composer edits
sed -i -e "s/boilerplace-php-package/$NEW_PROJECT_NAME/" composer.json
sed -i -e "s/\Template for new php packages/TODO/" composer.json
sed -i -e "s/\"name\": \"ebln\"/\"name\": \"$NAME\"/" composer.json
sed -i -e "s/\"email\":.*$/\"email\": \"$EMAIL\"/" composer.json
sed -i -e "s/Boilerplate/$NS2NDLEVEL/" composer.json

mkdir --parents src tests

cat >> README.md << EOF
#$NEW_PROJECT_NAME

TBD…
EOF



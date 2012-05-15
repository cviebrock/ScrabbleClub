LESS_DIR         = ./public/less
CSS_DIR          = ./public/css

LESSC           = `which lessc`
LESSC_FLAGS     = --compress

###


help:
	@echo
	@echo "Possible targets:"
	@echo "-----------------"
	@echo "help           - this"
	@echo "css            - build all CSS files from Less source"
	@echo "commit         - commit all built assets to git repository"
	@echo "all            - build all assets, then commit"
	@echo


all: css commit

css:
	${LESSC} ${LESSC_FLAGS} ${LESS_DIR}/bootstrap.less > ${CSS_DIR}/bootstrap.css

commit:
	git add ${CSS_DIR}
	git commit -e -m "Generated new static assets" ${CSS_DIR}


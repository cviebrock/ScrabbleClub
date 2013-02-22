LESS_DIR         = ./public/less
CSS_DIR          = ./public/css
JS_DIR           = ./public/js
IMG_DIR          = ./public/img
COMPONENTS_DIR   = ./components

TEMP_DIR         = /var/tmp

LESSC           = `which lessc`
LESSC_FLAGS     = --compress

UGLIFY          = `which uglifyjs`
UGLIFY_FLAGS    = --no-copyright

###


help:
	@echo
	@echo "Possible targets:"
	@echo "-----------------"
	@echo "help           - this"
	@echo "css            - build all CSS files from Less source"
	@echo "js             - build and minify all Javascript files from source"
	@echo "components     - build and minify all component files from source"
	@echo "commit         - commit all built assets to git repository"
	@echo "all            - build all assets, then commit"
	@echo


all: css js components commit

css:
	${LESSC} ${LESSC_FLAGS} ${LESS_DIR}/bootstrap.less > ${CSS_DIR}/scrabbleclub.css

js: js-bootstrap js-application

js-bootstrap:
	cat \
		${JS_DIR}/bootstrap-transition.js \
		${JS_DIR}/bootstrap-alert.js \
		${JS_DIR}/bootstrap-button.js \
		${JS_DIR}/bootstrap-carousel.js \
		${JS_DIR}/bootstrap-collapse.js \
		${JS_DIR}/bootstrap-dropdown.js \
		${JS_DIR}/bootstrap-modal.js \
		${JS_DIR}/bootstrap-tooltip.js \
		${JS_DIR}/bootstrap-popover.js \
		${JS_DIR}/bootstrap-scrollspy.js \
		${JS_DIR}/bootstrap-tab.js \
		${JS_DIR}/bootstrap-typeahead.js \
		> ${TEMP_DIR}/bootstrap.js
	${UGLIFY} ${UGLIFY_FLAGS} ${TEMP_DIR}/bootstrap.js > ${JS_DIR}/bootstrap.min.js
	rm ${TEMP_DIR}/bootstrap.js

js-application:
	${UGLIFY} ${UGLIFY_FLAGS} ${JS_DIR}/application.js > ${JS_DIR}/scrabbleclub.min.js

components: comp-fancybox

comp-fancybox:
	${UGLIFY} ${UGLIFY_FLAGS}	${COMPONENTS_DIR}/fancybox/source/jquery.fancybox.js > ${JS_DIR}/fancybox.min.js
	${LESSC} ${LESSC_FLAGS} ${COMPONENTS_DIR}/fancybox/source/jquery.fancybox.css > ${CSS_DIR}/fancybox.css
	cp ${COMPONENTS_DIR}/fancybox/source/*.gif ${CSS_DIR}
	cp ${COMPONENTS_DIR}/fancybox/source/*.png ${CSS_DIR}

commit:
	git add ${CSS_DIR} ${JS_DIR}
	git commit -e -m "Generated new static assets" ${CSS_DIR} ${JS_DIR}


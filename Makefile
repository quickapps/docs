# Makefile for Sphinx documentation
#

# Languages that can be built.
LANGS = en

# Dependencies to perform before running other builds.
# Clone the en/Makefile everywhere.
SPHINX_DEPENDENCIES = $(foreach lang, $(LANGS), $(lang)/Makefile)

#
# The various formats the documentation can be created in.
#
# Loop over the possible languages and call other build targets.
#
html: $(foreach lang, $(LANGS), html-$(lang))
epub: $(foreach lang, $(LANGS), epub-$(lang))
latex: $(foreach lang, $(PDF_LANGS), latex-$(lang))
pdf: $(foreach lang, $(PDF_LANGS), pdf-$(lang))

# Make the HTML version of the documentation with correctly nested language folders.
html-%: $(SPHINX_DEPENDENCIES)
	cd $* && make html LANG=$*

epub-%: $(SPHINX_DEPENDENCIES)
	cd $* && make epub LANG=$*

latex-%: $(SPHINX_DEPENDENCIES)
	cd $* && make latex LANG=$*

pdf-%: $(SPHINX_DEPENDENCIES)
	cd $* && make latexpdf LANG=$*

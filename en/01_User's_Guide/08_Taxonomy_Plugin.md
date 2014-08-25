Taxonomy Plugin
---------------

### About

The Taxonomy plugin allows you to classify the content of your website. To
classify content, you define vocabularies that contain related terms, and then
assign the vocabularies to content types.


### Uses

#### Creating vocabularies

Users with sufficient permissions	can create vocabularies and terms through the
Taxonomy page. The page listing the terms provides an interface for controlling
the order of the terms and sub-terms within a vocabulary, in a hierarchical
fashion. A controlled vocabulary classifying music by genre with terms and
sub-terms could look as follows:

	|- vocabulary: Music
	    |- term: Jazz
	        |- sub-term: Swing
	        |- sub-term: Fusion
	    |- term: Rock
	        |- sub-term: Country rock
	        |- sub-term: Hard rock


#### Assigning vocabularies to content types

Before you can use a new vocabulary to classify your content, a new **Taxonomy terms
field** must be added to a content type on its fields page. After choosing the terms
field, on the subsequent field settings page you can choose the desired vocabulary,
whether one or multiple terms can be chosen from the vocabulary, and other settings.
The same vocabulary can be added to multiple content types by using the terms field.


#### Classifying content

After the vocabulary is assigned to the content type's Taxonomy field, you can
start classifying content. The field with terms will appear on the content editing
screen when you edit or _add new content_.


#### Filtering contents by term

Each taxonomy works in combination with `Node` and `Search` plugin in order to
provide the ability to search and filter contents by an specific term. For example,
if the taxonomy term _Country Rock_ has the slug _country-rock_, then you will
find content "marked" with this term at the path `/find/term:country-rock`.
The RSS feed will use the path `/rss/term:country-rock`.
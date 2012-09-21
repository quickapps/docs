Search Engine
=============

The search engine system lets users search for specific nodes on your site for particular words, a search request looks as below:  

    http://www.your-domain.com/search/[seach-query]

Where [search-query] may contain one or more of the following keywords.


Keywords
========

### limit
Limit the amount of results returned by the search query.  

##### USAGE
	limit:5

---

### order
Order results by the specified field and the given direction (multiple orders must by separated by `|`).  
**Syntax:** {Model}.{field},{direction}|...  

##### USAGE
	Node.field,asc|Node.field2,desc

---

### term
Filter results by terms ID separed by comma.  

##### USAGE
	term:my-term1,my-term2

---

### vocabulary
Filter results by vocabularies ID separed by comma.
 
##### USAGE
	vocabulary:voc-1,voc2

---

### promote
Set to 1 to display promoted nodes only. Zero (0) will display all unpromoted ones.  

##### USAGE
	promote:1

---

### language
Show nodes matching the given language codes separed by comma. The wildcard `*` means any language.  
	
##### USAGE
Show nodes in: English or French or Spanish

	language:eng,fre,spa
	
Show nodes in any language

    language:*

---

### type
Show nodes of the specified type only. Multiple IDs must be separated by comma. 

##### USAGE
Show nodes of type `artcile` or `page` only
	
    type:article,page

---

### created
Show nodes matching the given creation date range.  
Syntax: created:[<lower date> TO <upper date>]  

* The `TO` word must be UPPERCASE
* lower/upper dates must be any valid PHP's date() expression
* The wildcard `*` means any date

##### USAGE
Show nodes created on 1976-03-06T23:59:59.999Z

    created:[1976-03-06T23:59:59.999Z TO *]
	// OR equivalent:
	created:[1976-03-06T23:59:59.999Z]

Show nodes created on 2012:

    created:[2012-01-01 TO 2012-12-01]
	
Show nodes created between 2008 and today:
	
	created:[2008-01-01 TO NOW]

---

### modified
Same as `created`.

---

### author
Show nodes created by the specified author(s).  
You can speicfy both user's ID or user's Email. Multiple authors must be saparated by comma.  

---

##### USAGE
	author:1,user@email.com,26
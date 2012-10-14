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


***



CCK Fields Search API
=====================

Since in v1.1 QuickApps CMS allows you to perform `find` conditions on any Fieldable Entity's CCK Field.  
For instance, lets suppose we have an User Fieldable Entity with three CCK Fields:

- User's Birthdate: field_user_birthdate
- User's Phone: field_user_phone
- User's Country: field_user_coutry

_Note: field_user_birthdate, field_user_phone & field_user_coutry are the machine-names of each CCK Field._

Now for example, we would like to search all users where phone matches `948 xxx xxx`. In your controller:

    public $uses = array('User.User');
    ...
    $this->User->find('all',
		array(
			'conditions' => array(
				'User.field_user_phone LIKE' => '948 ___ ___'
			)
		)
	);

***

QuickApps CMS automagically detects if the specified fields is a CCK Field or not. 
Anyway, if for some reason entity has a concrete field (those in entity's db-table) named same as the mahcine-name of any of its attached CCK fields
then you can simply prefix CCK field's machine-name with the `:` symbol to tell QuickApps that you are refering to the CCK field. e.g.:


    $this->User->find('all',
		array(
			'conditions' => array(
				'User.:name LIKE' => '%John%'
			)
		)
	);

If the User's db-table has a `name` column, it will be ignored.
	
***

## Tips & Tricks

Internally QuickApps stores searcheable information for each CCK Field independently,
and one _global_ record which contains the information of every CCK Field plus Entity's concrete fields.


##### Search on any Field

If you need to search some word on every CCK Field, you can use the special field `::` to indicate that you are refering to the whole
search-index-formation of the entity and not to an especifict CCK Field.

	$this->User->find('all',
		array(
			'conditions' => array(
				'User.:: LIKE' => '%some words%'
			)
		)
	);

In the User example. The code above will look the `some words` phrase on any CCK field of the user
(field_user_phone, field_user_coutry, field_user_birthdate) plus User's concrete fields.


##### Complex Finds

Similar as in [CakePHP](http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#complex-find-conditions), complex find conditions are
fully supported. e.g.:

	$this->User->find('all',
		array(
			'conditions' => array(
				'AND' => array(
					'OR' => array(
						array('User.field_user_phone LIKE' => '948%'),
						array('User.field_user_phone' => '123%')
					),
					array('User.field_user_coutry' => 'Utopain')
				)
			)
		)
	);

Search all user that own phone numbers begining with `948` (e.g 948 600 500) or `123` (e.g. 123 555 444), and they live in Utopain country.
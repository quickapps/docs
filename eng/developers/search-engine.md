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

Note: field_user_birthdate, field_user_phone & field_user_coutry are the machine-names of each CCK Field.

Now for example, we would like to search all users where phone matches `948 xxx xxx`. In your controller:

    public $uses = array('User.User');
    ...
    $this->User->find('all',
		array(
			'conditions' => array(
				'User.:field_user_phone LIKE' => '948 ___ ___'
			)
		)
	);

Dot syntax is optional, the code below will produce the same result:

    $this->User->find('all',
		array(
			'conditions' => array(
				':field_user_phone LIKE' => '948 ___ ___'
			)
		)
	);

As you see you must simply prefix field's machine-name with the `:` symbol to tell QuickApps that this fields is a CCK Field.


## Tips & Tricks

Internally QuickApps CMS organizes all entity's searchable data grouping them by CCK Field Handler.  
For instance, in our User example above. User has three CCK Fields `phone` & `country` that are handled
by the same Field Handler `FieldText`, and `birthdate` handled by `FieldDate`. Means `phone` and `country` holds
the same type of information which is handler by `FieldText`.  
Now, for the User example QuicKApps CMS will organize all CCK sercheable-data for each User as follow:


-	FieldText:
	-	field_user_phone: 948 123 321
	-	field_user_coutry: Utopian
-	FieldDate:
	-	field_user_birthdate: 1350095433
-	...


(The information above represent the searchable data for a particular user.)


##### Search by Field-Hanlder

	$this->User->find('all',
		array(
			'conditions' => array(
				'User.:FieldText LIKE' => '%something%'
			)
		)
	);

The above will search over any instance of FieldText. In the User example, it will search the `something` word 
over `field_user_phone` and `field_user_coutry`


##### Search on any Field-Handler

	$this->User->find('all',
		array(
			'conditions' => array(
				'User.: LIKE' => '%on any cck%'
			)
		)
	);

	OR

	$this->User->find('all',
		array(
			'conditions' => array(
				': LIKE' => '%on any cck%'
			)
		)
	);

The above will look for `on any cck` phrase on any CCK field of the user (field_user_phone, field_user_coutry, field_user_birthdate)


##### Complex Finds

Similar as in [CakePHP](http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#complex-find-conditions), some complex find conditions are
supported. e.g.:

	$this->User->find('all',
		array(
			'conditions' => array(
				'AND' => array(
					'OR' => array(
						array('User.:field_user_phone LIKE' => '948%'),
						array('User.:field_user_phone' => '123%')
					),
					array('User.:field_user_coutry' => 'Utopain')
				)
			)
		)
	);

Search all user that own phone numbers begining with `948` (e.g 948 600 500) or `123` (e.g. 123 555 444), and they live in Utopain country.
# Markdown Notes
is a PHP note-taking application that uses markdown syntax to format and organize text.
It's recommended to paste markdown content from a more competent IDE into the textarea.

### Features
* Unauthenticated User can view all categories and notes made public
* Authenticated User can create categories
* User can view notes formatted through Markdown syntax
* User can register and create accounts
* Application is protected from SQL injections
* Application is protected from XSS

### Dependencies
* Backend
1. PHP - Twig
2. MySQL
3. Shared Hosting
4. SSL

* Frontend
1. jQuery
2. Bootstrap
3. FontAwesome
4. Marked

### Model
```
USER
----> id (primary key)
----> name (varchar)
----> email (varchar)
----> password (8 required)
----> date_created (timestamp)

CATEGORY
----> id (primary key)
----> user_id (foreign key)
----> title (varchar)

NOTE
----> id (primary key)
----> cat_id (foreign key)
----> user_id (foreign key)
----> date_modified (timestamp)
----> title (varchar)
----> content (text)
----> share (boolean)
```

### Controllers
```
Home
----
Index()

User
----
Register()
Login()
isAuthenticated()

Category
--------
Index()
Add()
Edit($id)
Delete($id)
Read($id)

Note
----
Index()
Add($cat_id)
Edit($cat_id, $id)
Delete($id)
Read($id)
```

### Pages
```
Homepage -
My Categories - Read Categories, Delete Category
  |__ Add Category
  |__ Edit Category
Notes - Read Notes (10 per page), Delete Notes
  |___ Add Note

```

### Author
Neptune Michael Cabelin

### License
MIT

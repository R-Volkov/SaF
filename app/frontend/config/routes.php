<?php 
return [
	'' => 'main/index',
    'article/<id:\d+>' => 'main/single',
    //'<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
    'category/<name:[^\/]*>/page/<page:\d+>' => 'main/category',
    'category/<name:[^\/]*>' => 'main/category',
    'author/<name:[^\/]*>/page/<page:\d+>' => 'main/author',
    'author/<name:[^\/]*>' => 'main/author',
    'search/<search:[^\/]*>/page/<page:\d+>' => 'main/search',
    'search/<search:[^\/]*>' => 'main/search',
    'tag/<name:[^\/]*>/page/<page:\d+>' => 'main/tag',
    'tag/<name:[^\/]*>' => 'main/tag',
];

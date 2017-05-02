## profile
| Field Name   | Data Type                     |
|--------------|-------------------------------|
| username     | char(24) not null primary key |
| password     | bytea not null                |
| first_name   | char(24) not null             |
| last_name    | char(24) not null             |
| last_login   | timestamp not null            |
| ip           | cidr not null                 |



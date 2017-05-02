## profile
| Field Name   | Data Type                     |
|--------------|-------------------------------|
| username     | char(24) not null primary key |
| password     | bytea not null                |
| first_name   | char(24) not null             |
| last_name    | char(24) not null             |
| last_login   | timestamp not null            |
| ip           | cidr not null                 |

## video
| Field Name | Data Type                  |
|------------|----------------------------|
| uuid       | uuid not null primary key  |
| frames     | integer not null           |
| width      | integer not null           |
| height     | integer not null           |
| fps        | real not null              |
| username   | char(24) not null          |

FK username references profile


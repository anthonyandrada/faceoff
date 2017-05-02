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
###### FK username references profile
| Field Name | Data Type                  |
|------------|----------------------------|
| uuid       | uuid not null primary key  |
| frames     | integer not null           |
| width      | integer not null           |
| height     | integer not null           |
| fps        | real not null              |
| username   | char(24) not null          |
| processed  | boolean default false      |

## image
###### FK video_id references video
###### Composite PK: video_id, image_id
| Field Name | Data Type                   |
|------------|-----------------------------|
| uuid       | uuid not null primary key   |
| image_id   | bigint not null primary key |
| filename   | text not null               |
| yaw        | real not null               |
| pitch      | real not null               |
| roll       | real not null               |
| of_left    | point not null              |
| of_right   | point not null              |
| timm_left  | point not null              |
| timm_right | point not null              |
| data_1     | point not null              |

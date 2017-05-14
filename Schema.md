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
| Field Name   | Data Type                  |
|--------------|----------------------------|
| video_id     | bigint not null primary key|
| frames       | integer not null           |
| width        | integer not null           |
| height       | integer not null           |
| fps          | real not null              |
| username     | char(24) not null          |
| filename     | text not null              |
| fr_processed | integer default 0          |
| fd_processed | integer default 0          |
| pd_processed | integer default 0          |
| dt_processed | integer default 0          |

## image
###### FK video_id references video
###### Composite PK: video_id, image_id
| Field Name | Data Type                   |
|------------|-----------------------------|
| video_id   | bigint not null primary key |
| image_id   | bigint not null primary key |
| filename   | text not null               |
| yaw        | real default 0              |
| pitch      | real default 0              |
| roll       | real default 0              |
| of_left    | point default '(0,0)'       |
| of_right   | point default '(0,0)'       |
| timm_left  | point default '(0,0)'       |
| timm_right | point default '(0,0)'       |
| data_1     | point default '(0,0)'       |
| ...        | ...                         |
| data_68    | point default '(0,0)'       |

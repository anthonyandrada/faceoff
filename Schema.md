CS 160 Project PostgreSQL Tables

Table: profile
Field Name
Data Type
username
char(24) not null primary key
password
bytea not null
first_name
char(24) not null
last_name
char(24) not null
last_login
timestamp not null
ip
cidr not null

Table: video
Field Name
Data Type
uuid
uuid not null primary key
frames
integer not null
width
integer not null
height
integer not null
fps
real not null
username
char(24) not null
processed
boolean default false
FK username references profile

Table: image
Field Name
Data Type
uuid
uuid not null primary key
image_id
bigint not null primary key
filename
text not null
yaw
real not null
pitch
real not null
roll
real not null
of_left
point not null
of_right
point not null
timm_left
point not null
timm_right
point not null
data_1
point not null
data_2
point not null
data_3
point not null
data_4
point not null
data_5
point not null
data_6
point not null
data_7
point not null
data_8
point not null
data_9
point not null
data_10
point not null
data_11
point not null
data_12
point not null
data_13
point not null
data_14
point not null
data_15
point not null
data_16
point not null
data_17
point not null
data_18
point not null
data_19
point not null
data_20
point not null
data_21
point not null
data_22
point not null
data_23
point not null
data_24
point not null
data_25
point not null
data_26
point not null
data_27
point not null
data_28
point not null
data_29
point not null
data_30
point not null
data_31
point not null
data_32
point not null
data_33
point not null
data_34
point not null
data_35
point not null
data_36
point not null
data_37
point not null
data_38
point not null
data_39
point not null
data_40
point not null
data_41
point not null
data_42
point not null
data_43
point not null
data_44
point not null
data_45
point not null
data_46
point not null
data_47
point not null
data_48
point not null
data_49
point not null
data_50
point not null
data_51
point not null
data_52
point not null
data_53
point not null
data_54
point not null
data_55
point not null
data_56
point not null
data_57
point not null
data_58
point not null
data_59
point not null
data_60
point not null
data_61
point not null
data_62
point not null
data_63
point not null
data_64
point not null
data_65
point not null
data_66
point not null
data_67
point not null
data_68
point not null
FK video_id references video
Composite PK: video_id, image_id


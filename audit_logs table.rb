audit_logs table 
id	user_id	action	created_at

cases table 
case_idPrimary	varchar(10)	utf8mb4_general_ci		No	None			Change Change	Drop Drop	
More More
	2	title	varchar(255)	utf8mb4_general_ci		Yes	NULL			Change Change	Drop Drop	
More More
	3	date_filed	date			Yes	NULL			Change Change	Drop Drop	
More More
	4	status	enum('Open', 'Pending', 'Closed')	utf8mb4_general_ci		Yes	Open			Change Change	Drop Drop	
More More
	5	judge_id	int(11)			Yes	NULL			Change Change	Drop Drop	
More More
	6	created_byIndex	int(11)			Yes	NULL			Change Change	Drop Drop	
More More
	7	created_at

    case_status_history
    id	case_id	old_status	new_status	changed_at

hearings

Full texts	
hearing_id
case_id
hearing_date
court_name
created_at

judgemnts 

Full texts	
judgement_id
case_id
judgement_date
outcome
summary
created_at

pattern_flag

Full texts	
flag_id
case_id
flag_type
description
created_at

reports 
	report_id	report_type	generated_by	generated_at


users 

Full texts	
user_id
name
email
password
role
created_at

to ye mere sare table he inme kya chnages rahega wo batao 
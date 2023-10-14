/* 
Dear Robin,

I trust this finds you well. I do apologize for reaching out when I know full well that your hands are full especially at this time.

A couple of days ago, I reached to Viola when a site visit report that is in process (IMM Graduate School) could not be found either in my portal or Viola’s. Viola may have spoken to you about it at that time.

I have been up most of the night trying to trace another report, this time for HFPA which I recall sending to Japie for recommendation (although at this stage??). The report, HEQC REF VS 15_2023 H/PR305/RELO_Distillery Rd CPT_July2022  H/PR305/E002CAN H/PR305/E003CAN is not in my portal. It would normally be in recommendation approve or site visit report approve. I have checked the list of open processes also and it does not appear to be in Viola’s portal. Phumzile is not in the site visit flow so the report should not be with her. 

I would like to include the report in the Agenda for the May Ac meeting.

Kind regards,

Phumzile
 
*/


-- 1  Health and Fitness Professionals Academy (Pty) Ltd


-- Re-open current open Active Process

SELECT *
FROM tmp_aps
WHERE inst_site_app_id = 1033;


-- Re-open current open Active Process

UPDATE active_processes
SET status = 0
WHERE active_processes_id = 737946;

UPDATE tmp_aps
SET status = 0
WHERE active_processes_id = 737946;

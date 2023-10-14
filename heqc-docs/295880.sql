/* close duplicate
Dear Motheo,

Please not I have the following duplicate:

H/PR551/AR053CAN - Diploma in Office Administration and Management. ID 746218.

Kind regards,
Solly.
*/


-- 1 H/PR551/AR053CAN - Diploma in Office Administration and Management

UPDATE active_processes
SET status = 1
WHERE active_processes_id = 746218;

UPDATE tmp_aps
SET status = 1
WHERE active_processes_id = 746218;


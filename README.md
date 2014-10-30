fix-playa-field-data
====================

Pass a field_id which requires fixing and this plug-in will loop over all entries and repair the playa field data in that field using the exp_playa_relationships table as a reference.

  {exp:nf_fix_playa_field_data field_id="59"}

You should take a backup of your database before using the plug-in in a live environment.

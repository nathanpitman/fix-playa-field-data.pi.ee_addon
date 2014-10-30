fix-playa-field-data
====================

Sometimes you might find that the field data stored for a Pixel and Tonic Playa field in the exp_channel_data table does not directly correlate to the actual relationships in exp_playa_relationships. This becomes a problem if you want to search the field in question using an add-on like Low Search. If the data in the custom_field does not match that in the relationships table then you're screwed. This plug-in helps to fix that.

Pass the field_id which requires fixing and this plug-in will loop over all corresponding entries and relationships and repair the Playa field data in that field using the exp_playa_relationships table as a reference.

`{exp:nf_fix_playa_field_data field_id="59"}`

The behaviour of this plug-in is not reversable. You should take a backup of your database before using the plug-in in a live environment.

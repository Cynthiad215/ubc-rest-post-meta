# UBC Post Meta Data (Custom Fields)
Tags: UBC CMS

This plugin uses WP REST API to add a custom endpoint to retrieve custom field values from posts, not including hidden post metadata.

How to use: 
- [Required] To grab all custom fields attached to a specified post, add the following to the end of your website URL:  /wp-json/postmeta/v1/fields/{POST_ID} 

- [Optional] To grab the values of a single custom field, add the following to the end of your website URL:   /wp-json/postmeta/v1/fields/{POST_ID}/{CUSTOM_FIELD_KEY} 

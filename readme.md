# Events RSVP

#### Easily create an RSVP form and manage registrations on WordPress 

![Back-end admin](https://github.com/jklepatch/ersvp/raw/master/back-end.png)

Events RSVP is a plugin to create RSVP forms for events and manage registrations.

Events are created from a custom menu in the admin and integrated to the website
thanks to a custom widget, located in the widget menu.

Users can:
* register for the event
* register for the waiting list if the max registration limit has been reached
* receive confirmation emails after successful registration for the event or
for the waiting list

Admin can:
* see the list of people registered for the event and for the waiting list
* modify the max registration limit
* cancel any registration

## Installation

1. Install ERSVP via WordPress.org plugin directory
1. Activate the plugin

## Usage

1. Go to your WordPress admin
2. Go to Events > Add New
3. Create a new event with a title, max registrations limit (how many people can register) and date
4. Go to Events > Settings
5. Customize the notification emails to be sent to users upon registration for the event or for the waiting list
6. Go to Widgets 
7. In the Available Widgets pane, you should see a new widget called ERSVP
8. Drag and drop this widget to one of the widget area of your theme, on the right-hand side (sometime called sidebar)
9. Click on the down arrow of the ERSVP widget to expand it
10. Set the title that will appear on the front-end and select the event you want
11. Click on Save

## FAQ

### How to change the max registration limit?

1. Go to the event menu
2. Click on the event you want to modify
3. Update the max registration field with the new limit
4. Click on the Update button 

That's it ! The new max registration limit has been saved.

### How to cancel a registration?

1. Go to the event menu
2. Click on the event you want to modify
3. In the Registrations table, check the registrations to delete in the Delete column
4. Click on the Update button 

That's it ! The registration(s) you checked has(have) been deleted and the waiting list has been re-calculated

### How to change the event date?

1. Go to the event menu
2. Click on the event you want to modify
3. Update the date field with the new date
4. Click on the Update button

### How to change the title that appear above the RSVP form?

1. Go to the widget menu
2. On the right-hand side, you should see a Widget Area or a Sidebar pane
3. Find the ERSVP widget, and click on the down arrow to expand it
4. Change the title field
5. Click on Save 

That's it ! The new title has been saved

### How to change the emails sent to users after successful registration? 

1. Go to the Event menu
2. Go to the Settings menu
3. Find the ERSVP widget, and click on the down arrow to expand it
4. Change the title field
5. Change the subject and message fields for emails sent after a successful registration
for the event (registration Succesful section) and for the waiting list (Waiting List section) 

### How to change the form elements (Name, phone number, etc...)?

This feature has not been implemented yet, but it will be in a future release

## Troubleshooting

If you run into any problem, do not hesitate to [open an issue][issues]. I will keep on eye on this. 
Alternatively, you can send me an email at julien at julienklepatch dot com, or contact me on Twitter: [@jklepatch](https://twitter.com/jklepatch)

[issues]: https://github.com/jklepatch/ersvp/issues
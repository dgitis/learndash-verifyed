# Automatically Create VerifyEd Digital Certificates when Learners Complete your LearnDash Courses
Integrate LearnDash LMS with the VerifyEd digital certificate platform.

[LearnDash](https://www.learndash.com/) is a learning management system built on Wordpress.

[VerifyEd](https://www.verifyed.io/) is a digital certificate platform that uses blockchain to verify course completion credentials. One of the advantages of registering the certificates that you issue is that it lets online platforms verify that a course was actually completed. This allows people who complete your courses to show certification badges on LinkedIn for your courses.

This WordPress function automatically sends certification details from LearnDash to VerifyEd which will then issue a digital certificate and send an email to the learner with a link to their certificate.

It also sends errors and notifications to Slack.

## Before installing

Before installing the code, you will need a LearnDash course on Wordpress and a VerifyEd account with a certificate created for your LearnDash course.

### LearnDash course IDs

You will need to get the course ID for each LearnDash course that you want to integrate with VerifyEd.

To find a LearnDash course ID, in Wordpress got to LearnDash LMS > Courses and edit the desired course.

The course ID is the `post` parameter in the URL `/post.php?post=xxxx`.

### VerifyEd course and template IDs

You will need to have set up courses and templates (the VerifyEd certificate) for each course that you want to integrate.

To get your VerifyEd course ID, go to VerifyEd > Courses, select the desired course and click Edit in the popup.

The course ID is the `courseIdToEdit` parameter in the URL `certToEdit=x&courseId=x&courseIdToEdit=xxxx`.

To get your VerifyEd template ID, go to **VerifyEd** > **Credentials** > Credential Designer > **Design credential** and select **View or edit a template**.

The template ID is the number at the end of the URL `https://app.verifyed.io/designer/xxx`.

### VerifyEd API key

You will also need a VerifyEd API key.

In VerifyEd, go to **Automation**, and select **Generate API Key**. 

Take note of the key.

### Slack errors and notifications channels

If you don't want Slack errors and notifications, then you can skip this part, but you should probably delete the Slack code.

1. Log in to your Slack web client as an admin.
2. Select **Configure apps** which loads a page in a new tab.
3. At the top-right of the new page, select **Build**, **Create New App**, and **From scratch**.
4. Give your app a name, select the desired workspace, and continue.
5. In the new page that opens, select **Incoming webhooks**.
6. Enable incoming webhooks.
7. Create an "errors" webhook and map it to the Slack channel that you want to receive errors on.
8. Take note of the Webhooks URL. You will need everything after `https://hooks.slack.com/services/`.
9. Create a "notifications" webhook and map it to the Slack channel that you want to receive notifications on.
10. Take note of the Webhooks URL. You will need everything after `https://hooks.slack.com/services/`.


## Using the code

Copy and paste the code to the bottom of your `functions.php` file.

### Edit the functions.php file

You will need to map your LearnDash course IDs to VerifyEd course and template IDs as seen in the below block.

```
if ($course_id == <insert course id>) {   // replace with learndash course id
    $verifyed_template_id = <insert template id>;   // replace with verifyed template id
    $verifyed_course_id = <insert course id>;   // replace with verifyed course id
} 
```

If you have multiple courses, you'll need to add `elseif` blocks for each course.

### Edit your wp-config.php file

You will need to add constants to your `wp-config.php` file with your VerifyEd API key, and your Slack errors and notifications URL fragments.

Add the following lines to your `wp-config.php` file:

```
define( 'VERIFYED_API_KEY', '' );

define( 'SLACK_APPLICATION_ERROR_ID', '' );

define( 'SLACK_APPLICATION_NOTIFICATION_ID', '' );
```

Be sure to insert the relevant API key and Slack URL fragments before saving.
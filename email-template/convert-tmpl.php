<?php

/**
 * Quick utility script to convert the pook-newsletter.tpl.php file to JSON format.
 *
 * Usage: php ./convert-tmpl.php > tmpl.json
 *
 * Then you can use the AWS CLI. May need to install PIP: https://pip.pypa.io/en/latest/installing/
 * I used the --user flag to install PIP and AWS and it ended up in ~/Library/Python/2.7/bin, so I just added that to my PATH.
 *
 * Then you will need to setup a user with access keys and configure the AWS CLI to use it.
 *
 * Then you can create/update the template.
 * aws ses create-template --cli-input-json file://tmpl.json
 * aws ses update-template --cli-input-json file://tmpl.json
 */

$json_data = [
  'TemplateName' => 'RYOESP-Sample',
  'SubjectPart' => "Newsletter template sample",
];

$html_template = file_get_contents('pook-newsletter.tpl.php');

$tags_to_convert = [
  '<?php echo $preheader; ?>' => '{{preheader}}',
  '<?php echo $header; ?>' => '{{header}}',
  '<?php foreach ($articles as $article): ?>' => '{{#each articles}}',
  '<?php echo $article[\'img_url\']; ?>' => '{{img_url}}',
  '<?php echo $article[\'img_alt\']; ?>' => '{{img_alt}}',
  '<?php echo $article[\'title\']; ?>' => '{{title}}',
  '<?php echo $article[\'subhead\']; ?>' => '{{subhead}}',
  '<?php echo $article[\'url\']; ?>' => '{{url}}',
  '<?php endforeach; ?>' => '{{/each}}',
  '<?php echo $email; ?>' => '{{email}}',
  '<?php echo date(\'Y\'); ?>' => '{{year}}',
];

foreach ($tags_to_convert as $php_tag => $handlebar_tag) {
  $html_template = str_replace($php_tag, $handlebar_tag, $html_template);
}

$json_data['TextPart'] = '';
$json_data['HtmlPart'] = $html_template;

$overall = [
  'Template' => $json_data,
];

print json_encode($overall);


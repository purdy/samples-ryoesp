<?php

/**
 * Simple sending script.
 *
 * The email template needs these variables:
 *  - preheader
 *  - header
 *  - articles
 *    - img_url
 *    - img_alt
 *    - title
 *    - subhead
 *    - url
 *  - year
 */

// In this example, the variables are NOT user-specific.
$template_data = [
  'preheader' => "Open this to read all about some great stuff!",
  'header' => "Aren't you glad you opened this?",
  'articles' => [
    [
      'img_url' => 'https://cdn.cnn.com/cnnnext/dam/assets/190603144803-06-apple-wwdc-2019-itunes-apps-screengrab-exlarge-169.jpg',
      'img_alt' => 'iTunes will be replaced by three desktop apps called Music, Podcasts and TV.',
      'title' => "Apple pulls the plug on iTunes",
      'subhead' => 'RIP iTunes as we know it. Apple breaks up iconic music platform',
      'url' => 'https://www.cnn.com/2019/06/03/tech/itunes-apple-wwdc/index.html',
    ],
    [
      'img_url' => 'https://cdn.cnn.com/cnnnext/dam/assets/190603144803-06-apple-wwdc-2019-itunes-apps-screengrab-exlarge-169.jpg',
      'img_alt' => 'James Holzhauer',
      'title' => "James Holzhauer's historic 'Jeopardy!' winning streak is over",
      'subhead' => 'James Holzhauer\'s historic "Jeopardy!" winning streak came to an end Monday night -- and it wasn\'t even a wrong answer that undid him.',
      'url' => 'https://www.cnn.com/2019/06/03/entertainment/james-holzhauer-jeopardy-streak-over-loses-trnd/index.html',
    ],
    [
      'img_url' => 'https://www.charlotteobserver.com/latest-news/b9qghq/picture231081033/alternates/FREE_1140/G.A.%20Kohler.jpg',
      'img_alt' => 'Cape Hatteras National Seashore',
      'title' => "Remains of century-old schooner done in by a hurricane emerge on Outer Banks",
      'subhead' => 'The charred remains of a century-old schooner that wrecked in a 1933 hurricane can be spotted in the sand of a popular Outer Banks island for now. But there’s always a chance nature could cover the remains again.',
      'url' => 'https://www.newsobserver.com/news/state/north-carolina/article231079733.html',
    ],
  ],
  'year' => date('Y'),
];

$recipients = [
  'jason@purdy.info',
  'bounce@simulator.amazonses.com',
  'ooto@simulator.amazonses.com',
  'complaint@simulator.amazonses.com',
  'success@simulator.amazonses.com',
];

$email = [
  'ConfigurationSetName' => 'RYOESP-Sample',
  'Source' => '"Purdy Cool Stuff" <stuff@purdy.cool>',
  'ReplyToAddresses' => ['"Jason Purdy" <jason@purdy.cool>'],
  'DefaultTags' => [
    [
      'Name' => 'SID',
      'Value' => 0,
    ],
  ],
  'DefaultTemplateData' => json_encode($template_data),
  'Destinations' => [],
  'Template' => $template,
];



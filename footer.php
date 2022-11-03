<?php
namespace VisitMarche\ThemeTail;

use VisitMarche\ThemeTail\Lib\Twig;

wp_footer();
Twig::rendPage(
    '@VisitTail/_footer.html.twig',
    [

    ]
);
echo '
</body>
</html>';
<?php

namespace App\Traits;

use App\Hateoas\Hateoas;

trait HateoasResource
{
    protected function links(Hateoas $hateoas): array
    {
        $links = [];

        $hateoasMethods = get_class_methods($hateoas);
        foreach ($hateoasMethods as $hateoasMethod) {
            if (str_ends_with($hateoasMethod, 'Link')) {
                $link = $hateoas->$hateoasMethod(); 
                
                if(!empty($link)) {
                    $links[] = $link;
                }
            }
        }

        return $links;
    }
}

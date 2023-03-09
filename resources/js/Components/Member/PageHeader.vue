
<template>
    <a-page-header 
        style="text-transform: capitalize;" 
        :breadcrumb="{ routes }">
        <template #title>
            <div >
                <slot name="header" />
            </div>
        </template>
    </a-page-header>
</template>

<script>

import menu from './menu.js';

export default {
    props:['menuKeys'],
    data() {
        return {
            
        }
    },
    computed: {
        routes() {
            let arr = []
            
            let parent = menu.find( e => e.key == this.menuKeys.menuOpenKey.filter(n => n)[0] ) 

            if( parent === undefined){

                let path = route().current().split('.')

                if( path === undefined ){
                    return    
                }

                path.splice(-1)

                path.map( (v,i) => { arr.push( 
                        {path:"",breadcrumbName: v.replace("_"," ") } 
                    )
                })

            }else{
                let children = parent.children.find( e => e.key == this.menuKeys.menuSelectKey[0] ) 

                if( children === undefined){
                    arr = [{ path:"" , breadcrumbName: parent.title} ]
                }else{
                    arr = [{ path:"" , breadcrumbName: parent.title}, { path:"", breadcrumbName: children.title} ]
                }
                
            }

            return arr 

            // console.log(this.menuKeys.menuSelectKey)
        }
    },
    methods: {
        
    }
}

</script>
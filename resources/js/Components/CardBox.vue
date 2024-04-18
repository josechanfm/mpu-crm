<template>
<div :class="`border border-${color} rounded-lg mt-5`">
    <h1 :class="`bg-${color} text-white p-4 rounded-t-lg text-lg`">
        {{title}}
    </h1>
    <div class="p-4">
        <slot name="content" />
    </div>
</div>
</template>

<script>
export default {
    props:[
        'title',
        'themeColor'
    ],
    data() {
        return {
            
        }
    },
    computed: {
        color(){
            if(this.themeColor){
                return this.themeColor
            }else{
                return 'sky-500'
            }
        }
    }
}
</script>
<script>
export default {
  props: ['state'],
  data(){
    return {
      promoteLabel:{"-1":"Fail","0":"Uncertain","1":"Pass","2":"Promoted"},
    }
  }
}
</script>

<template>
  <button v-show="(state===-1)" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
    {{promoteLabel[state]}}
  </button>
  <button v-show="(state===0)" class="bg-transparent hover:bg-yellow-500 text-yellow-700 font-semibold hover:text-white py-2 px-4 border border-yellow-500 hover:border-transparent rounded">
    {{promoteLabel[state]}}
  </button>
  <button v-show="(state===1)" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
    {{promoteLabel[state]}}
  </button>
  <button v-show="(state===2)" class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
    {{promoteLabel[state]}}
  </button>
</template>
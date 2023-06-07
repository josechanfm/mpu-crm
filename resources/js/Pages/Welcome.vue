<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
<!-- component -->
<!-- Header -->
<header>
  <!-- navbar and menu -->
  <nav class="shadow">

  <div class="flex justify-between items-center py-6 px-10 container mx-auto">

    <div>
      <h1 class="text-2xl font-bold bg-gradient-to-tr from-indigo-600 to-green-600 bg-clip-text text-transparent hover:cursor-pointer">WIRE</h1>
    </div>

    <div>
      
      <div class="hover:cursor-pointer sm:hidden">
        <spnan class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></spnan>
        <spnan class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></spnan>
        <spnan class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></spnan>
      </div>
      <div class="flex items-center">

        <ul class="sm:flex space-x-4 hidden items-center">
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Home</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">About</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Services</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Products</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Contact</a></li>
        </ul>

        <div class="md:flex items-center hidden space-x-4 ml-8 lg:ml-12">
          <h1 class="text-text-gray-600  py-2 hover:cursor-pointer hover:text-indigo-600"><inertia-link :href="route('login')">登入</inertia-link></h1>
          <h1 class="text-text-gray-600  py-2 hover:cursor-pointer px-4 rounded text-white bg-gradient-to-tr from-indigo-600 to-green-600 hover:shadow-lg"><inertia-link :href="route('manage.login')">後台</inertia-link></h1>
        </div>
      </div>
    </div>
  </div>
  </nav>
</header>
<main>
  <!-- section hero -->
  <section>
    <div class="bg-gray-100 sm:grid grid-cols-5 grid-rows-2 px-4 py-6 min-h-full lg:min-h-screen space-y-6 sm:space-y-0 sm:gap-4">

    <div class="h-96 col-span-4 bg-gradient-to-tr from-indigo-800 to-indigo-500 rounded-md flex items-center">
      <div class="ml-20 w-80">
      <h2 class="text-white text-4xl">行政管理</h2>
      <p class="text-indigo-100 mt-4 capitalize font-thin tracking-wider leading-7">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed, dolore?</p>

      <a href="#" class="uppercase inline-block mt-8 text-sm bg-white py-2 px-4 rounded font-semibold hover:bg-indigo-100">get start</a>
      </div>

    </div>
    <div class="h-96 col-span-1 ">
      <div class="bg-white py-3 px-4 rounded-lg flex justify-around items-center ">
   <input type="text" placeholder="seach" class=" bg-gray-100 rounded-md  outline-none pl-2 ring-indigo-700 w-full mr-2 p-2">
  <span><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor ">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg></span>
      </div>
      <div class="bg-white  rounded-md">

      <h1 class="text-center text-xl my-4  bg-white py-2 rounded-md border-b-2 cursor-pointer  text-gray-600">Service</h1>
      <div class="bg-white rounded-md list-none  text-center ">
        <li class="py-3 border-b-2"><inertia-link :href="route('inquiry.index')">Inquiry</inertia-link></li>
        <li class="py-3 border-b-2"><a href="#" class="list-none  hover:text-indigo-600">Models</a></li>
        <li class="py-3 border-b-2"><a href="#" class="list-none  hover:text-indigo-600">Pricing</a></li>
        <li class="py-3 border-b-2"><a href="#" class="list-none  hover:text-indigo-600">Hire</a></li>
        <li class="py-3 "><a href="#" class="list-none border-b-2 hover:text-indigo-600">Business</a></li>
      </div>
      </div>
    </div>
    </div>

  </section>

</main>
</template>

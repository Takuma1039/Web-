<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet" />

<x-app-layout>
    <div class="flex bg-gray-100">
      <div class="flex overflow-hidden">
        <!-- Content Body -->
        <div class=" flex-1 overflow-auto p-4">
        <!--検索form-->
          <div class=" mx-auto mt-5 w-screen max-w-screen-md leading-6">
            <form class="relative flex w-full flex-col justify-between rounded-lg border sm:flex-row sm:items-center sm:p-0">
              <div class="flex">
                <label class="focus-within:ring h-14 rounded-md bg-gray-200 px-2 ring-emerald-200 border-none" for="category">
                  <select class="border-transparent focus:border-transparent focus:ring-0 bg-transparent pl-6 py-4 outline-none border-none" name="spot[spot_category_id]" id="category">
                    @foreach($spotcategories as $spotcategory)
                      <option value="{{ $spotcategory->id }}">{{ $spotcategory->name }}</option>
                    @endforeach
                  </select>
                </label>
                <input type="name" name="search" value="" class="ml-1 h-14 w-full cursor-text rounded-md border py-4 pl-6 outline-none ring-emerald-200 sm:border-0 sm:pr-40 sm:pl-12 focus:ring" placeholder="City, Address, Zip :" />
              </div>
              <button type="submit" class="mt-2 inline-flex h-12 w-full items-center justify-center rounded-md bg-emerald-500 px-10 text-center align-middle text-base font-medium normal-case text-white outline-none ring-emerald-200 ring-offset-1 sm:absolute sm:right-0 sm:mt-0 sm:mr-1 sm:w-32 focus:ring">Search</button>
            </form>
            <!--<div class="mt-4 divide-y rounded-b-xl border px-4 shadow-lg sm:mr-32 sm:ml-28">-->
            <!--  <div class="cursor-pointer px-4 py-2 text-gray-600 hover:bg-emerald-400 hover:text-white"><span class="m-0 font-medium">Ca</span> <span>lifornia</span></div>-->
            <!--  <div class="cursor-pointer px-4 py-2 text-gray-600 hover:bg-emerald-400 hover:text-white"><span class="m-0 font-medium">Ca</span> <span>nada</span></div>-->
            <!--  <div class="cursor-pointer px-4 py-2 text-gray-600 hover:bg-emerald-400 hover:text-white"><span class="m-0 font-medium">Ca</span> <span>mbodia</span></div>-->
            <!--  <div class="cursor-pointer px-4 py-2 text-gray-600 hover:bg-emerald-400 hover:text-white"><span class="m-0 font-medium">Ca</span> <span>meo</span></div>-->
            <!--  <div class="cursor-pointer px-4 py-2 text-gray-600 hover:bg-emerald-400 hover:text-white"><span class="m-0 font-medium">Ca</span> <span>rsville</span></div>-->
            <!--</div>-->
          </div>


              <!--<h1 class="text-2xl font-semibold">Welcome to our website</h1>-->
              <!--<p>... Content goes here ...</p>-->
              
          <div id="default-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
              <!-- Item 1 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://www.nta.co.jp/media/tripa/static_contents/nta-tripa/articles/images/000/000/418/medium/8342c3cf-fbb8-402a-9bd8-37fd7f1b8c33.jpg?1550646782" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 2 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://img.freepik.com/free-photo/fuji-mountain-and-kawaguchiko-lake-in-morning-autumn-seasons-fuji-mountain-at-yamanachi-in-japan_335224-102.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 3 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://img.freepik.com/free-photo/purple-nature-landscape-with-vegetation_23-2150859581.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 4 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://designwork-s.net/blog/wp-content/uploads/scientifantastic1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 5 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://www.a-kimama.com/wp-content/uploads/2016/06/20160628-1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
            </div>
            
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
              <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
            </div>
            <!-- Slider controls -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
              </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
              </span>
            </button>
          </div>
	            
          <!--image-->  
            <h1 class="text-2xl font-semibold my-2">人気スポットランキング<h1></h1>
            <a href="" class="flex items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center">
              <span>
                View More
              </span>
              <svg class="w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                viewBox="0 0 24 24" class="w-6 h-6 ml-2">
                <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>
            <!--spotimage-->
            <div class="max-w-screen-xl mx-auto p-5">
              <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-10">
                <div class="rounded overflow-hidden shadow-lg">
                  <a href="#"></a>
                    <div class="relative">
                        <a href="/spots/create">
                          <img class="w-full"
                            src="https://images.pexels.com/photos/196667/pexels-photo-196667.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=1&amp;w=500"
                            alt="Sunset in the mountains">
                          <div class="hover:bg-transparent transition duration-300 absolute bottom- top-0 right-0 left-0 bg-gray-900 opacity-25"></div>
                        </a>
                        <!--<a href="#!">-->
                        <!--  <div class="absolute bottom-0 left-0 bg-indigo-600 px-4 py-2 text-white text-sm hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">-->
                        <!--    Photos-->
                        <!--  </div>-->
                        <!--</a>-->

                        <a href="/spots/1">
                          <div class="text-sm absolute top-0 right-0 bg-indigo-600 px-4 text-white rounded-full h-16 w-16 flex flex-col items-center justify-center mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                            <span class="font-bold">27</span>
                            <small>March</small>
                          </div>
                        </a>
                      </div>
                    
                      <div class="px-6 py-4">
                        <a href="#" class="font-semibold text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out">
                          Best View in Newyork City</a>
                        <p class="text-gray-500 text-sm">
                          The city that never sleeps
                        </p>
                      </div>
                      <!--<div class="px-6 py-4 flex flex-row items-center">-->
                      <!--  <span href="#" class="py-1 text-sm font-regular text-gray-900 mr-1 flex flex-row items-center">-->
                      <!--    <svg height="13px" width="13px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"-->
                      <!--      xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"-->
                      <!--      style="enable-background:new 0 0 512 512;" xml:space="preserve">-->
                      <!--      <g>-->
                      <!--        <g>-->
                      <!--          <path d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z"></path>-->
                      <!--        </g>-->
                      <!--      </g>-->
                      <!--    </svg>-->
                      <!--    <span class="ml-1">6 mins ago</span>-->
                      <!--  </span>-->
                      <!--</div>-->
                      <div class="px-6 py-4 flex flex-row items-center">
                        <span class="text-teal-600 font-semibold">
                          <span>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                          <span>
                          </span>
                          <span class="ml-2 text-gray-600 text-sm">34 reviews</span>
                      </div>
                  </div>
                  
                  <div class="rounded overflow-hidden shadow-lg">
                    <a href="#"></a>
                      <div class="relative">
                        <a href="#">
                          <img class="w-full"
                            src="https://images.pexels.com/photos/1653877/pexels-photo-1653877.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=1&amp;w=500"
                            alt="Sunset in the mountains">
                          <div
                            class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25">
                          </div>
                        </a>
                        <a href="#!">
                          <div
                            class="absolute bottom-0 left-0 bg-indigo-600 px-4 py-2 text-white text-sm hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                            Photos
                          </div>
                        </a>
                        <a href="!#">
                          <div
                            class="text-sm absolute top-0 right-0 bg-indigo-600 px-4 text-white rounded-full h-16 w-16 flex flex-col items-center justify-center mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                            <span class="font-bold">20</span>
                            <small>March</small>
                          </div>
                        </a>
                      </div>
                      <div class="px-6 py-4">
                        <a href="#"
                          class="font-semibold text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out">Best
                          Pizza in Town
                        </a>
                        <p class="text-gray-500 text-sm">
                          The collection of best pizza images in Newyork city
                        </p>
                      </div>
                      <!--<div class="px-6 py-4 flex flex-row items-center">-->
                      <!--  <span href="#"-->
                      <!--    class="py-1 text-sm font-regular text-gray-900 mr-1 flex flex-row justify-between items-center">-->
                      <!--    <svg height="13px" width="13px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"-->
                      <!--      xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"-->
                      <!--      style="enable-background:new 0 0 512 512;" xml:space="preserve">-->
                      <!--      <g>-->
                      <!--        <g>-->
                      <!--          <path -->
                      <!--            d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256 c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128 c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z">-->
                      <!--          </path>-->
                      <!--        </g>-->
                      <!--      </g>-->
                      <!--    </svg>-->
                      <!--    <span class="ml-1">3 mins read</span>-->
                      <!--  </span>-->
                      <!--</div>-->
                  </div>
            
                  <div class="rounded overflow-hidden shadow-lg">
                    <a href="#"></a>
                    <div class="relative">
                      <a href="#">
                        <img class="w-full"
                          src="https://images.pexels.com/photos/257816/pexels-photo-257816.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=1&amp;w=500"
                          alt="Sunset in the mountains">
                        <div
                          class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25">
                        </div>
                      </a>
                      <a href="#!">
                        <div
                          class="absolute bottom-0 left-0 bg-indigo-600 px-4 py-2 text-white text-sm hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                          Photos
                        </div>
                      </a>
                      <a href="!#">
                        <div
                          class="text-sm absolute top-0 right-0 bg-indigo-600 px-4 text-white rounded-full h-16 w-16 flex flex-col items-center justify-center mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                          <span class="font-bold">15</span>
                          <small>April</small>
                        </div>
                      </a>
                    </div>
                    <div class="px-6 py-4">
                      <a href="#"
                        class="font-semibold text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out">Best
                        Salad Images ever
                      </a>
                      <p class="text-gray-500 text-sm">
                        The collection of best salads of town in pictures
                      </p>
                    </div>
                    <!--<div class="px-6 py-4 flex flex-row items-center">-->
                    <!--  <span href="#"-->
                    <!--    class="py-1 text-sm font-regular text-gray-900 mr-1 flex flex-row justify-between items-center">-->
                    <!--    <svg height="13px" width="13px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"-->
                    <!--      xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"-->
                    <!--      style="enable-background:new 0 0 512 512;" xml:space="preserve">-->
                    <!--      <g>-->
                    <!--        <g>-->
                    <!--          <path-->
                    <!--            d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256 c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128 c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z">-->
                    <!--          </path>-->
                    <!--        </g>-->
                    <!--      </g>-->
                    <!--    </svg>-->
                    <!--    <span class="ml-1">6 mins read</span>-->
                    <!--  </span>-->
                    <!--</div>-->
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</x-app-layout>

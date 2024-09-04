<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet" />

<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <div class="h-screen flex overflow-hidden bg-gray-200">
          <!-- Sidebar -->
          <div class="absolute z-20 text-white w-56 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300" id="sidebar">
            <!-- Your Sidebar Content -->
            <div class="flex flex-col flex-1 overflow-y-auto">
              <nav class="flex flex-col flex-1 overflow-y-auto bg-gradient-to-b from-gray-700 to-blue-500 px-2 py-4 gap-10 rounded-2xl">
                <!--myicon-->
                <a href="" class="h-10 w-10 overflow-hidden rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="h-10 w-10 p-2 text-white bg-gray-500 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                    </path>
                  </svg>
                </a>
                
                <!--<div>-->
                <!--  <a href="/" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">-->
                <!--    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"-->
                <!--      stroke="currentColor">-->
                <!--      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
                <!--        d="M4 6h16M4 12h16M4 18h16" />-->
                <!--    </svg>-->
                <!--       Dashboard-->
                <!--  </a>-->
                <!--</div>-->
                <div class="flex flex-col flex-1 gap-3"> 
                  <a href="/home" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 80 px">
                      <path fill="currentColor" fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6l2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2z" clip-rule="evenodd" />
                    </svg>
                      マイページ
                  </a>
                  <a href="/profile" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="margin-right: 8px">
                      <path fill="currentColor" d="M12 4a5 5 0 1 1-5 5a5 5 0 0 1 5-5m0-2a7 7 0 1 0 7 7a7 7 0 0 0-7-7m10 28h-2v-5a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v5H2v-5a7 7 0 0 1 7-7h6a7 7 0 0 1 7 7zm0-26h10v2H22zm0 5h10v2H22zm0 5h7v2h-7z" />
                    </svg>
                      プロフィール
                  </a>
                  <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 8px">
                      <path fill="none" stroke="currentColor" stroke-width="2" d="M16 7h3v4h-3zm-7 8h11M9 11h4M9 7h4M6 18.5a2.5 2.5 0 1 1-5 0V7h5.025M6 18.5V3h17v15.5a2.5 2.5 0 0 1-2.5 2.5h-17" />
                    </svg>
                      旅行プラン作成
                  </a>
                    <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="margin-right: 8px">
                        <path fill="currentColor" d="M21.053 20.8c-1.132-.453-1.584-1.698-1.584-1.698s-.51.282-.51-.51s.51.51 1.02-2.548c0 0 1.413-.397 1.13-3.68h-.34s.85-3.51 0-4.7c-.85-1.188-1.188-1.98-3.057-2.547s-1.188-.454-2.547-.396c-1.36.058-2.492.793-2.492 1.19c0 0-.85.056-1.188.396c-.34.34-.906 1.924-.906 2.32s.283 3.06.566 3.625l-.337.114c-.284 3.283 1.13 3.68 1.13 3.68c.51 3.058 1.02 1.756 1.02 2.548s-.51.51-.51.51s-.452 1.245-1.584 1.698c-1.132.452-7.416 2.886-7.927 3.396c-.512.51-.454 2.888-.454 2.888H29.43s.06-2.377-.452-2.888c-.51-.51-6.795-2.944-7.927-3.396zm-12.47-.172c-.1-.18-.148-.31-.148-.31s-.432.24-.432-.432s.432.432.864-2.16c0 0 1.2-.335.96-3.118h-.29s.144-.59.238-1.334a10.01 10.01 0 0 1 .037-.996l.038-.426c-.02-.492-.107-.94-.312-1.226c-.72-1.007-1.008-1.68-2.59-2.16c-1.584-.48-1.01-.384-2.16-.335c-1.152.05-2.112.672-2.112 1.01c0 0-.72.047-1.008.335c-.27.27-.705 1.462-.757 1.885v.28c.048.654.26 2.45.47 2.873l-.286.096c-.24 2.782.96 3.118.96 3.118c.43 2.59.863 1.488.863 2.16s-.432.43-.432.43s-.383 1.058-1.343 1.44l-.232.092v5.234h.575c-.03-1.278.077-2.927.746-3.594c.357-.355 1.524-.94 6.353-2.862zm22.33-9.056c-.04-.378-.127-.715-.292-.946c-.718-1.008-1.007-1.68-2.59-2.16c-1.583-.48-1.007-.384-2.16-.335c-1.15.05-2.11.672-2.11 1.01c0 0-.72.047-1.008.335c-.27.272-.71 1.472-.758 1.89h.033l.08.914c.02.23.022.435.027.644c.09.666.21 1.35.33 1.59l-.286.095c-.24 2.782.96 3.118.96 3.118c.432 2.59.863 1.488.863 2.16s-.43.43-.43.43s-.054.143-.164.34c4.77 1.9 5.927 2.48 6.28 2.833c.67.668.774 2.316.745 3.595h.48V21.78l-.05-.022c-.96-.383-1.344-1.44-1.344-1.44s-.433.24-.433-.43s.433.43.864-2.16c0 0 .804-.23.963-1.84V14.66c0-.018 0-.033-.003-.05h-.29s.216-.89.293-1.862z" />
                      </svg>
                      みんなの投稿
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 8px">
                        <path fill="currentColor" d="M12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20m0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20m5-9H7a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2m-2 4H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2M9 9h6a1 1 0 0 0 0-2H9a1 1 0 0 0 0 2" />
                      </svg>
                      口コミ投稿
                    </a>
                </div>
              </nav>
            </div>
          </div>

        <!-- Content -->
          <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <div class="bg-white shadow">
                <div class="container mx-auto ">
                    <div class="flex justify-between items-center py-4 px-2">
                        <h1 class="text-xl font-semibold">Few Days-Trip</h1>
                        <!--windowbar-->
                        <button class="text-gray-500 hover:text-gray-600" id="open-sidebar">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                          </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Content Body -->
            <div class=" flex-1 overflow-auto p-4">
              <!--検索form-->
              <form class="max-w-lg mx-auto">
                <div class="flex">
                  <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your Email</label>
                  <button id="dropdown-button" data-dropdown-toggle="dropdown" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">All categories 
                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                  </button>
                  <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                      <li>
                        <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mockups</button>
                      </li>
                      <li>
                        <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Templates</button>
                      </li>
                      <li>
                        <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Design</button>
                      </li>
                      <li>
                        <button type="button" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logos</button>
                      </li>
                    </ul>
                  </div>
                  <div class="relative w-full">
                    <input type="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Search Mockups, Logos, Design Templates..." required />
                      <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span class="sr-only">Search</span>
                      </button>
                  </div>
                </div>
              </form>

              <!--<h1 class="text-2xl font-semibold">Welcome to our website</h1>-->
              <!--<p>... Content goes here ...</p>-->
              
              <body class="bg-gray-100 flex items-center justify-center min-h-screen">
		            <div class="relative w-full max-w-4xl mx-auto">
			          <!-- Carousel wrapper -->
			            <div class="overflow-hidden relative rounded-lg">
				            <div
					            class="flex transition-transform duration-500 ease-in-out transform"
					            id="carousel"
				              >
					            <!-- Slide 1 -->
					            <div class="min-w-full">
						            <img
							            src="https://via.placeholder.com/800x400/FFB6C1/000000"
							            alt="Slide 1"
							            class="w-full h-full object-cover"
						            />
					            </div>
					            <!-- Slide 2 -->
					            <div class="min-w-full">
						            <img
							            src="https://via.placeholder.com/800x400/87CEFA/000000"
							            alt="Slide 2"
							            class="w-full h-full object-cover"
						            />
					            </div>
					            <!-- Slide 3 -->
					            <div class="min-w-full">
						            <img
							            src="https://via.placeholder.com/800x400/98FB98/000000"
							            alt="Slide 3"
							            class="w-full h-full object-cover"
						            />
					            </div>
				            </div>
			            </div>

			            <!-- Navigation buttons -->
			            <button
				            class="absolute top-1/2 left-0 transform -translate-y-1/2 p-3 bg-gray-700 bg-opacity-50 rounded-full text-white hover:bg-opacity-75 focus:outline-none"
				            onclick="scrollCarousel(-1)">
				            <svg
					            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
					            xmlns="http://www.w3.org/2000/svg">
					            <path
						            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
					            </path>
				            </svg>
			            </button>
			            <button
				            class="absolute top-1/2 right-0 transform -translate-y-1/2 p-3 bg-gray-700 bg-opacity-50 rounded-full text-white hover:bg-opacity-75 focus:outline-none"
				            onclick="scrollCarousel(1)">
				            <svg 
				              class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
					            xmlns="http://www.w3.org/2000/svg">
					            <path
						            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
					            </path>
				            </svg>
			            </button>
		            </div>

		            <script>
			            let currentIndex = 0;

			            function scrollCarousel(direction) {
				            const carousel = document.getElementById("carousel");
				            const totalSlides = carousel.children.length;
				            currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
				            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
			            }
		            </script>
	            </body>
	            
              <!--image-->  
              <h1 class="text-2xl font-semibold">人気スポットランキング<h1></h1>
              <a href="" class="flex items-center text-indigo-700 border border-indigo-600 py-2 px-6 gap-2 rounded inline-flex items-center">
                <span>
                  View More
                </span>
                <svg class="w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  viewBox="0 0 24 24" class="w-6 h-6 ml-2">
                  <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
              </a>
              <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16">
                <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
                  <div class="rounded overflow-hidden shadow-lg">
                    <a href="#"></a>
                      <div class="relative">
                        <a href="/create">
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

                        <a href="!#">
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

      <script>
        const sidebar = document.getElementById('sidebar');
        const openSidebarButton = document.getElementById('open-sidebar');
    
        openSidebarButton.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('-translate-x-full');
        });

        // Close the sidebar when clicking outside of it
        document.addEventListener('click', (e) => {
          if (!sidebar.contains(e.target) && !openSidebarButton.contains(e.target)) {
              sidebar.classList.add('-translate-x-full');
          }
        });
      </script>
  </div>
</x-app-layout>

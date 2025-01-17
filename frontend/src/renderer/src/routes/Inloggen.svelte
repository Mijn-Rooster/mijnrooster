<script lang="ts">
   import MenuBar from "../components/MenuBar.svelte";
   import { navigate } from "../stores/RouterStore";
    import { Button, Footer, Label, Input } from 'flowbite-svelte';
    import { ArrowLeftOutline, UserSolid, LockSolid } from 'flowbite-svelte-icons';
  </script>
  
  <MenuBar />

  <main>
    <div class="flex-grow flex flex-col items-center justify-center ">
      <div class="w-full max-w-md p-4 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <h2 class="text-3xl font-semibold text-center text-gray-800 dark:text-white">Inloggen</h2>
        <form class="mt-6" action="#" method="POST">
          <div>
            <Label for="leerlingnummer" class="block text-sm text-gray-800 dark:text-gray-200">leerlingnummer</Label>
            <Input type="text" id="leerlingnummer" placeholder="leerlingnummer" class="w-full px-4 py-2 mt-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:border-primary-400 focus:outline-none focus:ring dark:text-gray-300"> 
              <UserSolid slot="left" class="w-4 h-4" />
            </Input>
          </div>

          <div class="mt-4">
            <Label for="password" class="block text-sm text-gray-800 dark:text-gray-200">Wachtwoord</Label>
            <Input type="password" id="password" placeholder="Wachtwoord" class="w-full px-4 py-2 mt-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:border-primary-400 focus:outline-none focus:ring dark:text-gray-300">
              <LockSolid slot="left" class="w-4 h-4" />
            </Input>
            
          </div>
          <div class="mt-6">
            <Button type="submit" class="w-full py-2 text-white bg-primary-500 rounded-md hover:bg-primary-600 focus:outline-none focus:ring">Inloggen</Button>
          </div>
        </form>
      </div>
    </div>
   
    <Footer class="absolute bottom-0 start-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
      <Button class="gap-2 px-2" on:click={() => navigate("/")}><ArrowLeftOutline/>Terug</Button>
    </Footer>
  </main>


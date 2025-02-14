<script lang="ts">
  import ScheduleItem from "./ScheduleItem.svelte";
  import { Button } from "flowbite-svelte";
  import { ArrowLeftOutline, ArrowRightOutline } from "flowbite-svelte-icons";
  import { onMount } from "svelte";
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { Spinner } from "flowbite-svelte";
  import { retrieveSchedule } from "../../services/api.service";
  import ErrorBanner from "../ErrorBanner.svelte";
  import type { ErrorModel } from "../../models/error.model";
  import type { UserModel } from "../../models/user.model";
</script>


<div class="mx-auto w-full max-w-[1000px] flex flex-col gap-4">
  <!-- Date navigation -->
  <div class="flex justify-between my-5 align-content-center">
    <!-- Previous -->
    <Button class="p-2 w-10" >
      <ArrowLeftOutline />
    </Button>

    <!-- Current week -- Is het nodig om weeknumer erbij te zetten? zoniet kan je gwn verwijderen -->
    <h2 class="text-xl font-bold text-center">Februari | Week 7 </h2> 

    <!-- Next -->
    <Button class="p-2 w-10" >
      <ArrowRightOutline />
    </Button>
  </div>
</div>

<!-- Week schedule -->
<div class="grid grid-cols-5 gap-4">
  <!-- Monday -->
  <div>
    <h3 class="text-lg font-bold text-center">Maandag 10</h3>
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u1
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>
    
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u2
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u3
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u4
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u5
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>
  </div>

  

  <!-- Tuesday -->
  <div>
    <h3 class="text-lg font-bold text-center">Dinsdag 11 </h3>
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u1
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u2
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u3
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    
  </div>

  <!-- Wednesday -->
  <div>
    <h3 class="text-lg font-bold text-center">Woensdag 12 </h3>
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u1
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u2
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u3
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u4
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u5
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u6
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u7
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u8
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    
  </div>

  <!-- Thursday -->
  <div>
    <h3 class="text-lg font-bold text-center">Donderdag 13 </h3>
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u1
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u2
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u3
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u4
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u5
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u6
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>
  </div>

  <!-- Friday -->
  <div>
    <h3 class="text-lg font-bold text-center">Vrijdag 14 </h3>
    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u1
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u2
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u3
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>

    <div class="bg-secondary-700 rounded-lg h-20 flex items-center justify-between p-6 mb-3">
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        u4
      </span>
      <div class="flex flex-col items-center">
        
        <b>Bv</b>
        <p>Bv2 - pro</p>
      </div>
    </div>
  </div>
</div>

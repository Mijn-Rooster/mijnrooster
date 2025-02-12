<script lang="ts">
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { timeConverter } from "../../services/time.service";

  export let item: ScheduleItemModel;
</script>

<div
  class="flex p-4 rounded-lg flex-row items-center gap-5"
  class:bg-red-300={item.changes.cancelled}
  class:bg-yellow-200={!item.changes.cancelled && item.type != "lesson"}
  class:bg-secondary-700={!item.changes.cancelled && item.type == "lesson"}
>
  <!-- hour indication -->
  {#if item.lessonNumberStart != ""}
    {#if item.lessonNumberStart === item.lessonNumberEnd}
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        {item.lessonNumberStart}
      </span>
    {:else}
      <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
        {item.lessonNumberStart} - {item.lessonNumberEnd}
      </span>
    {/if}
  {:else}
    <span class="text-white px-4 py-2 w-fit rounded-3xl bg-primary-700">
      {timeConverter(item.start)}
      -
      {timeConverter(item.end)}
    </span>
  {/if}

  <div>
    <!-- Subject name -->
    <div class="flex flex-row gap-1">
      {#if item.subjectsFriendlyNames.length > 0}
        <p class="font-bold">{item.subjectsFriendlyNames.join(", ")}</p>
      {:else}
        <p class="font-bold">{item.subjects}</p>
      {/if}
    </div>
    <!-- Location and teacher -->
    <div class="flex flex-row gap-1">
      {#if item.locations.length == 0}
        <span class="text-red-800">Geen lokaal</span>
      {:else}
        <p>{item.locations.join(", ")}</p>
      {/if}
      {#if item.changes.locationChanged}
        <span class="text-red-900 font-bold">!</span>
      {/if}
      <p>-</p>
      {#if item.teachers.length == 0}
        <span class="text-red-800">Geen docent</span>
      {:else}
        <p>{item.teachers.join(", ")}</p>
      {/if}
      {#if item.changes.teacherChanged}
        <span class="text-red-900 font-bold">!</span>
      {/if}
    </div>
    <!-- (Change) description -->
    {#if item.description && item.type != "lesson"}
      <p class="text-sm">{item.description}</p>
    {/if}
    {#if item.changes.changeDescription}
      <p class="text-sm">{item.changes.changeDescription}</p>
    {/if}
  </div>
</div>

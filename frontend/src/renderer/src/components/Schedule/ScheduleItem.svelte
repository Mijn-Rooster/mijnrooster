<script lang="ts">
  import type { ScheduleItemModel } from "../../models/scheduleItem.model";
  import { timeConverter } from "../../services/time.service";

  export let item: ScheduleItemModel;

</script>

<div class="flex p-4 rounded-lg flex-row items-center gap-5" class:bg-red-700={item.changes.cancelled} class:bg-secondary-700={!item.changes.cancelled}>
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

  <!-- details -->
  <div>
    {#if item.subjectsFriendlyNames.length > 0}
      <p class="font-bold">{item.subjectsFriendlyNames.join(", ")}</p>
    {:else}
      <p class="font-bold">{item.subjects}</p>
    {/if}
    <div class="flex flex-row gap-1">
      <p>{item.locations.join(", ")}</p>
      {#if item.changes.locationChanged}
      <span class="text-yellow-400">!</span>
      {/if}
      {#if item.locations.length = 0}
        <span class="text-red-400">Geen lokaal</span>
      {/if}
      <p>-</p>
      <p>{item.teachers.join(", ")}</p>
      {#if item.changes.teacherChanged}
      <span class="text-yellow-400">!</span>
      {/if}
      {#if item.teachers.length = 0}
        <span class="text-red-400">Geen docent</span>
      {/if}
    </div>
  </div>
</div>

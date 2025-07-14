'use client';

import dynamic from 'next/dynamic';

// This component now contains the dynamic import and is marked as a client component.
const DeliveryZoneMap = dynamic(() => import('@/components/DeliveryZoneMap'), {
  ssr: false,
});

export default function MapLoader() {
  // The position of the restaurant (e.g., Restaurant 1 in Gulshan)
  const restaurantPosition: [number, number] = [23.7925, 90.4125];

  const handleZoneSave = (zonePayload: any) => {
    console.log("Saving zone to API with payload:", zonePayload);
    // In a real application, you would make a fetch() call here
    // to your backend API endpoint: POST /api/v1/restaurants/{id}/zones
    alert(`Zone data ready to be sent to API:\n\n${JSON.stringify(zonePayload, null, 2)}`);
  };

  return (
    <DeliveryZoneMap
      restaurantPosition={restaurantPosition}
      onZoneSave={handleZoneSave}
    />
  );
}
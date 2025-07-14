'use client';

import { useState, useRef } from 'react';
import { MapContainer, TileLayer, FeatureGroup } from 'react-leaflet';
import { EditControl } from 'react-leaflet-draw';
import * as L from 'leaflet'; // Import Leaflet for type definitions

// Define types for our component's props and state
type ZoneType = 'radius' | 'polygon';

interface Point {
  lat: number;
  lng: number;
}

interface ZoneData {
  type: ZoneType;
  value: number | Point[];
}

interface DeliveryZoneMapProps {
  restaurantPosition: [number, number];
  onZoneSave: (payload: { name: string; type: ZoneType; value: number | Point[] }) => void;
}

// Type for the event from react-leaflet-draw
interface CreatedEvent {
  layerType: 'circle' | 'polygon';
  layer: L.Layer; // Use base Layer type for the event
}

function DeliveryZoneMap({ restaurantPosition, onZoneSave }: DeliveryZoneMapProps) {
  const [zoneType, setZoneType] = useState<ZoneType>('radius');
  const [zoneData, setZoneData] = useState<ZoneData | null>(null);
  const [zoneName, setZoneName] = useState<string>('');
  const featureGroupRef = useRef<L.FeatureGroup>(null);

  const handleCreated = (e: CreatedEvent) => {
    const { layerType, layer } = e;
    let drawnData: ZoneData;

    if (layerType === 'circle') {
      const circleLayer = layer as L.Circle;
      drawnData = {
        type: 'radius',
        value: Math.round(circleLayer.getRadius()),
      };
    } else {
      const polygonLayer = layer as L.Polygon;
      const latlngs = polygonLayer.getLatLngs()[0] as L.LatLng[];
      drawnData = {
        type: 'polygon',
        value: latlngs.map(p => ({ lat: p.lat, lng: p.lng })),
      };
    }

    if (featureGroupRef.current) {
      featureGroupRef.current.clearLayers();
      featureGroupRef.current.addLayer(layer);
    }

    setZoneData(drawnData);
  };

  const handleSaveClick = () => {
    if (!zoneName || !zoneData) {
      alert('Please enter a zone name and draw a zone on the map.');
      return;
    }
    const finalZonePayload = {
      name: zoneName,
      type: zoneData.type,
      value: zoneData.value,
    };
    onZoneSave(finalZonePayload);
  };

  return (
    <div className="flex flex-col md:flex-row h-[85vh] gap-6">
      {/* Control Panel */}
      <div className="w-full md:w-1/3 bg-white p-6 rounded-lg shadow-md border">
        <h2 className="text-2xl font-bold mb-4">Create Delivery Zone</h2>
        <div className="space-y-4">
          <div>
            <label htmlFor="zoneName" className="block text-sm font-medium text-gray-700">Zone Name</label>
            <input
              type="text"
              id="zoneName"
              value={zoneName}
              onChange={(e: React.ChangeEvent<HTMLInputElement>) => setZoneName(e.target.value)}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500"
              placeholder="e.g., Gulshan Main Area"
            />
          </div>
          <div>
            <span className="block text-sm font-medium text-gray-700">Zone Type</span>
            <div className="mt-2 flex gap-4">
              <button onClick={() => setZoneType('radius')} className={`px-4 py-2 rounded-md text-sm font-medium ${zoneType === 'radius' ? 'bg-sky-600 text-white' : 'bg-gray-200 text-gray-700'}`}>Radius</button>
              <button onClick={() => setZoneType('polygon')} className={`px-4 py-2 rounded-md text-sm font-medium ${zoneType === 'polygon' ? 'bg-sky-600 text-white' : 'bg-gray-200 text-gray-700'}`}>Polygon</button>
            </div>
          </div>
          <div className="pt-4">
            <button
              onClick={handleSaveClick}
              className="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors"
            >
              Save Zone
            </button>
          </div>
          {zoneData && (
            <div className="mt-4 p-3 bg-gray-50 rounded-md border text-xs">
              <h4 className="font-semibold">Captured Data:</h4>
              <pre className="mt-1 whitespace-pre-wrap break-all">{JSON.stringify(zoneData, null, 2)}</pre>
            </div>
          )}
        </div>
      </div>

      {/* Map Container */}
      <div className="flex-1 rounded-lg overflow-hidden shadow-md border">
        <MapContainer center={restaurantPosition} zoom={14} scrollWheelZoom={true} style={{ height: '100%', width: '100%' }}>
          <TileLayer
            attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          />
          <FeatureGroup ref={featureGroupRef}>
            <EditControl
              position="topright"
              onCreated={handleCreated as any} // Using 'as any' because of potential type mismatch in the library
              draw={{
                rectangle: false,
                polyline: false,
                marker: false,
                circlemarker: false,
                circle: zoneType === 'radius',
                polygon: zoneType === 'polygon',
              }}
              edit={{
                edit: false,
                remove: true,
              }}
            />
          </FeatureGroup>
        </MapContainer>
      </div>
    </div>
  );
}

export default DeliveryZoneMap;
<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
  // Create a new client
  public function createClient(array $data)
  {
    return Client::create($data);
  }

  // Update an existing client
  public function updateClient(int $id, array $data): bool
  {
    $client = Client::find($id);
    if ($client) {
      return $client->update($data);
    }
    return false;
  }

  // Soft delete a client
  public function deleteClient(int $id): bool
  {
    $client = Client::find($id);
    if ($client) {
      return $client->delete();
    }
    return false;
  }

  // Restore a soft deleted client
  public function restoreClient(int $id): bool
  {
    $client = Client::onlyTrashed()->find($id);
    if ($client) {
      return $client->restore();
    }
    return false;
  }


  // Find a client by ID
  public function findClientById(int $id): ?Client
  {
    return Client::find($id);
  }

  // Get soft-deleted clients
  public function getDeletedClients(): Collection
  {
    return Client::onlyTrashed()->get();
  }
}

<?php
    trait Auxiliary {
        public function pwdHasher(string $pwd): string {
            return password_hash($pwd, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536, // 64 MiB (in KiB)
                'time_cost'   => 2,
                'threads'     => 1,
            ]);
        }

        public function verifyHash(string $pwd, string $hash): bool {
            return $hash !== '' && password_verify($pwd, $hash);
        }
    }
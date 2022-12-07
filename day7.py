import fileinput

root = {}
stack = []
current = root

for line in fileinput.input():
    match line.strip().split():
        case ["$", "cd", "/"]:
            stack = []
            current = root
        case ["$", "cd", ".."]:
            current = stack.pop()
        case ["$", "cd", dir]:
            stack.append(current)
            current = current[dir]
        case ["$", "ls"]:
            pass
        case ["dir", dir]:
            current[dir] = {}
        case [size, filename]:
            current[filename] = int(size)
        case _:
            raise Exception

MAX = 100000


def get_size(object: int | dict, sum_list: list):
    if type(object) == dict:
        sommetje = sum([get_size(x, sum_list) for x in object.values()])
        if sommetje <= MAX:
            sum_list.append(sommetje)
        return sommetje
    return object


sum_list = []
root_size = get_size(root, sum_list)

print(sum(sum_list))

TOTAL_NEEDED = 30000000
DISK_SIZE = 70000000

still_needed = TOTAL_NEEDED - (DISK_SIZE - root_size)


def get_size(object: int | dict, sum_list: list):
    if type(object) == dict:
        sommetje = sum([get_size(x, sum_list) for x in object.values()])
        if sommetje >= still_needed:
            sum_list.append(sommetje)
        return sommetje
    return object


lijstje = []

get_size(root, lijstje)

print(min(lijstje))
